jQuery(function ($) {
  async function loadInlineSvg(url, mount) {
    const res = await fetch(url);
    const txt = await res.text();
    mount.innerHTML = txt;
    return mount.querySelector("svg");
  }

  function applyViewport(g, viewport) {
    g.setAttribute(
      "transform",
      `translate(${viewport.x} ${viewport.y}) scale(${viewport.scale})`
    );
  }

  function renderPoints(g, points, onClickPoint) {
    g.querySelectorAll(".svgmap-point").forEach((n) => n.remove());

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
      c.style.cursor = "pointer";
      c.addEventListener("click", (e) => {
        e.stopPropagation();
        onClickPoint(p, e);
      });
      g.appendChild(c);
    });
  }

  function showTooltip(root, html, clientX, clientY) {
    const tip = root.querySelector(".svgmap__tooltip");
    tip.innerHTML = html;
    tip.style.display = "block";

    const rect = root.getBoundingClientRect();
    tip.style.left = clientX - rect.left + 12 + "px";
    tip.style.top = clientY - rect.top - 12 + "px";
  }

  function hideTooltip(root) {
    const tip = root.querySelector(".svgmap__tooltip");
    tip.style.display = "none";
  }

  $(".svgmap").each(async function () {
    const root = this;
    const stage = root.querySelector(".svgmap__stage");

    const svgUrl = root.dataset.svgUrl;
    const viewport = JSON.parse(
      root.dataset.viewport || '{"x":0,"y":0,"scale":1}'
    );
    const points = JSON.parse(root.dataset.points || "[]");

    const svg = await loadInlineSvg(svgUrl, stage);
    if (!svg) return;

    // g viewport
    let g = svg.querySelector("#viewport");
    if (!g) {
      g = document.createElementNS("http://www.w3.org/2000/svg", "g");
      g.setAttribute("id", "viewport");
      const children = Array.from(svg.childNodes);
      children.forEach((n) => {
        if (n.nodeType === 1) g.appendChild(n);
      });
      svg.appendChild(g);
    }

    applyViewport(g, viewport);

    renderPoints(g, points, (point, ev) => {
      if (!point.term_id) {
        showTooltip(
          root,
          "<div>Este punto no tiene país asignado.</div>",
          ev.clientX,
          ev.clientY
        );
        return;
      }

      $.post(SVGMAP_FRONT.ajax, {
        action: "svg_mapa_hoteles",
        nonce: SVGMAP_FRONT.nonce,
        term_id: point.term_id,
      }).done((resp) => {
        if (!resp || !resp.success) {
          showTooltip(
            root,
            "<div>Error cargando hoteles.</div>",
            ev.clientX,
            ev.clientY
          );
          return;
        }

        const data = resp.data;
        const items = (data.hoteles || [])
          .map(
            (h) =>
              `<li><a target="_blank" rel="noopener noreferrer" href="${
                h.link || "#"
              }">${h.title}</a></li>`
          )
          .join("");

        const html = `
          <h4>${data.pais}</h4>
          ${
            items
              ? `<ul>${items}</ul>`
              : `<div>No hay hoteles en este país.</div>`
          }
        `;
        showTooltip(root, html, ev.clientX, ev.clientY);
      });
    });

    // cerrar tooltip al click fuera
    root.addEventListener("click", () => hideTooltip(root));
  });
});
