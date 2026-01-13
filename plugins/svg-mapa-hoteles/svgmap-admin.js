(function () {
  const stage = document.getElementById("svgmap-stage");
  if (!stage) return;

  const inputViewport = document.getElementById("svgmap-viewport");
  const inputPoints = document.getElementById("svgmap-points");
  const list = document.getElementById("svgmap-point-list");
  const termTpl = document.getElementById("svgmap-term-options");

  const resetBtn = document.getElementById("svgmap-reset");
  const saveBtn = document.getElementById("svgmap-save");
  const svgUrlInput = document.querySelector('input[name="svg_url"]');

  let viewport = safeJson(stage.dataset.viewport, { x: 0, y: 0, scale: 1 });
  let points = safeJson(stage.dataset.points, []);
  let svgEl = null;
  let viewportG = null;

  let dragging = false;
  let last = { x: 0, y: 0 };

  function safeJson(str, fallback) {
    try {
      return JSON.parse(str || "");
    } catch (e) {
      return fallback;
    }
  }

  async function loadSvg(url) {
    stage.innerHTML = "";
    if (!url) {
      stage.innerHTML =
        "<p style='padding:12px'>Pega la URL del SVG y guarda/recarga.</p>";
      return;
    }
    const res = await fetch(url);
    const txt = await res.text();
    stage.innerHTML = txt;

    svgEl = stage.querySelector("svg");
    if (!svgEl) {
      stage.innerHTML = "<p style='padding:12px'>No se pudo cargar el SVG.</p>";
      return;
    }

    // Asegura tamaño responsive dentro del stage
    svgEl.style.width = "100%";
    svgEl.style.height = "100%";
    svgEl.style.display = "block";
    svgEl.style.cursor = "grab";

    // Crea un <g id="viewport"> y mete el contenido dentro (para transformar todo)
    if (!svgEl.querySelector("#viewport")) {
      const g = document.createElementNS("http://www.w3.org/2000/svg", "g");
      g.setAttribute("id", "viewport");

      // mueve todos los hijos existentes al g
      const children = Array.from(svgEl.childNodes);
      children.forEach((n) => {
        if (n.nodeType === 1) g.appendChild(n);
      });
      svgEl.appendChild(g);
    }

    viewportG = svgEl.querySelector("#viewport");

    applyViewport();
    renderPoints();
    renderList();
  }

  function applyViewport() {
    if (!viewportG) return;
    viewportG.setAttribute(
      "transform",
      `translate(${viewport.x} ${viewport.y}) scale(${viewport.scale})`
    );
    inputViewport.value = JSON.stringify(viewport);
  }

  function screenToSvg(clientX, clientY) {
    const pt = svgEl.createSVGPoint();
    pt.x = clientX;
    pt.y = clientY;
    const ctm = svgEl.getScreenCTM().inverse();
    const p = pt.matrixTransform(ctm);
    return p;
  }

  function svgToViewportCoords(svgX, svgY) {
    // Convertimos a coords dentro del viewportG (inverso del transform)
    // Como aplicamos translate + scale, para guardar anclado al mapa guardamos coords "sin transformar":
    // Necesitamos coordenadas del sistema del SVG original, o sea dentro del viewportG ANTES del transform:
    // p' = (p - translate) / scale
    return {
      x: (svgX - viewport.x) / viewport.scale,
      y: (svgY - viewport.y) / viewport.scale,
    };
  }

  function renderPoints() {
    if (!viewportG) return;

    // Borra puntos existentes
    viewportG.querySelectorAll(".svgmap-point").forEach((n) => n.remove());

    points.forEach((p) => {
      const c = document.createElementNS(
        "http://www.w3.org/2000/svg",
        "circle"
      );
      c.setAttribute("class", "svgmap-point");
      c.setAttribute("cx", p.x);
      c.setAttribute("cy", p.y);
      c.setAttribute("r", "6");
      c.setAttribute("fill", "#ffdd57");
      c.setAttribute("stroke", "#000");
      c.setAttribute("stroke-width", "1");
      viewportG.appendChild(c);
    });

    inputPoints.value = JSON.stringify(points);
  }

  function renderList() {
    list.innerHTML = "";
    if (!points.length) {
      list.innerHTML =
        "<p style='margin:0'>No hay puntos. Haz click en el mapa para crear uno.</p>";
      return;
    }

    points.forEach((p, idx) => {
      const row = document.createElement("div");
      row.className = "svgmap-point-item";

      const left = document.createElement("div");
      left.className = "svgmap-inline";

      const badge = document.createElement("span");
      badge.className = "svgmap-badge";
      badge.textContent = `#${idx + 1}`;

      const sel = document.createElement("select");
      sel.innerHTML = termTpl.innerHTML;
      sel.value = p.term_id || "";

      sel.addEventListener("change", () => {
        points[idx].term_id = parseInt(sel.value || "0", 10);
        inputPoints.value = JSON.stringify(points);
      });

      left.appendChild(badge);
      left.appendChild(sel);

      const del = document.createElement("button");
      del.type = "button";
      del.className = "button";
      del.textContent = "Eliminar";
      del.addEventListener("click", () => {
        points.splice(idx, 1);
        renderPoints();
        renderList();
      });

      row.appendChild(left);
      row.appendChild(del);

      list.appendChild(row);
    });
  }

  function addPointAt(clientX, clientY) {
    if (!svgEl) return;

    const p = screenToSvg(clientX, clientY);
    const v = svgToViewportCoords(p.x, p.y);

    points.push({
      id: "p" + Date.now(),
      x: +v.x.toFixed(2),
      y: +v.y.toFixed(2),
      term_id: 0,
    });

    renderPoints();
    renderList();
  }

  // Pan
  stage.addEventListener("mousedown", (e) => {
    if (!svgEl) return;
    dragging = true;
    last = { x: e.clientX, y: e.clientY };
    svgEl.style.cursor = "grabbing";
  });

  window.addEventListener("mouseup", () => {
    dragging = false;
    if (svgEl) svgEl.style.cursor = "grab";
  });

  window.addEventListener("mousemove", (e) => {
    if (!dragging) return;
    const dx = e.clientX - last.x;
    const dy = e.clientY - last.y;
    last = { x: e.clientX, y: e.clientY };

    viewport.x += dx;
    viewport.y += dy;
    applyViewport();
  });

  // Zoom con rueda (centrado donde está el mouse)
  stage.addEventListener(
    "wheel",
    (e) => {
      if (!svgEl) return;
      e.preventDefault();

      const delta = e.deltaY > 0 ? 0.92 : 1.08;
      const newScale = Math.min(6, Math.max(0.4, viewport.scale * delta));

      viewport.scale = +newScale.toFixed(4);
      applyViewport();
    },
    { passive: false }
  );

  // Click: crea punto (si no estás arrastrando)
  let moved = false;
  stage.addEventListener("mousemove", () => {
    if (dragging) moved = true;
  });
  stage.addEventListener("click", (e) => {
    if (!svgEl) return;
    if (moved) {
      moved = false;
      return;
    }
    addPointAt(e.clientX, e.clientY);
  });

  resetBtn.addEventListener("click", () => {
    viewport = { x: 0, y: 0, scale: 1 };
    applyViewport();
  });

  saveBtn.addEventListener("click", () => {
    // Esto guarda en los hidden inputs; luego le das "Actualizar" al post para persistir.
    inputViewport.value = JSON.stringify(viewport);
    inputPoints.value = JSON.stringify(points);
    alert("Listo. Ahora dale click a 'Actualizar' para guardar en WordPress.");
  });

  // Carga inicial (cuando ya existe svg_url)
  loadSvg(svgUrlInput.value);

  // Si cambian la URL del SVG, recarga
  svgUrlInput.addEventListener("change", () => loadSvg(svgUrlInput.value));
})();
