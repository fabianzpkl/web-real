jQuery(function ($) {
  // =========================================================
  // 1) HOTELES: Slick + Filtros (select viejo + botones + select mobile)
  // =========================================================
  (function initHoteles() {
    const $wrap = $(".carrusel-hoteles");
    if (!$wrap.length) return;

    const $selectOld = $("#filtro_categorias"); // value: "todos" o term_id (ej: "23")
    const $buttons = $(".filter-tags-hotels .tag-hotel"); // data-filter: "all" o "categoria-23"
    const $selectMobile = $("#filterHotelsSelect"); // value: "all" o "categoria-23"

    // Init slick UNA vez
    if (!$wrap.hasClass("slick-initialized")) {
      $wrap.slick({
        arrows: true,
        dots: false,
        infinite: false,
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        pauseOnFocus: true,
        pauseOnHover: true,
        variableWidth: true,
        swipe: true,

        // Flechas custom (Font Awesome)
        prevArrow:
          '<button type="button" class="slick-arrow slick-prev custom-slick-arrow" aria-label="Anterior"><i class="fa-solid fa-chevron-left"></i></button>',
        nextArrow:
          '<button type="button" class="slick-arrow slick-next custom-slick-arrow" aria-label="Siguiente"><i class="fa-solid fa-chevron-right"></i></button>',

        responsive: [
          { breakpoint: 1024, settings: { slidesToShow: 2 } },
          { breakpoint: 768, settings: { slidesToShow: 1 } },
        ],
      });
    }

    function setActiveByFilter(filter) {
      // Activo en botones
      if ($buttons.length) {
        $buttons.removeClass("is-active");
        $buttons.filter(`[data-filter="${filter}"]`).addClass("is-active");
      }

      // Sincroniza select mobile
      if ($selectMobile.length) {
        $selectMobile.val(filter);
      }

      // Sincroniza select viejo (#filtro_categorias)
      // Si filter = "categoria-23" => value debe ser "23"
      if ($selectOld.length) {
        if (filter === "all") {
          $selectOld.val("todos");
        } else if (
          typeof filter === "string" &&
          filter.indexOf("categoria-") === 0
        ) {
          $selectOld.val(filter.replace("categoria-", ""));
        }
      }
    }

    function applySlickFilter(filter) {
      // Limpia filtro anterior
      $wrap.slick("slickUnfilter");

      // Aplica filtro nuevo
      if (filter && filter !== "all") {
        $wrap.slick("slickFilter", "." + filter);
      }

      // ✅ Siempre al primer slide
      $wrap.slick("slickGoTo", 0, true);
      $wrap.slick("setPosition");
    }

    // --- Eventos ---

    // Select viejo (term_id)
    if ($selectOld.length) {
      $selectOld.on("change", function () {
        const val = $(this).val(); // "todos" o "23"
        const filter = val === "todos" ? "all" : "categoria-" + val;

        setActiveByFilter(filter);
        applySlickFilter(filter);
      });
    }

    // Botones
    if ($buttons.length) {
      $buttons.on("click", function () {
        const filter = $(this).data("filter"); // "all" o "categoria-23"
        setActiveByFilter(filter);
        applySlickFilter(filter);
      });
    }

    // Select mobile (ya viene como all/categoria-xx)
    if ($selectMobile.length) {
      $selectMobile.on("change", function () {
        const filter = $(this).val();
        setActiveByFilter(filter);
        applySlickFilter(filter);
      });
    }

    // Estado inicial
    setActiveByFilter("all");
    applySlickFilter("all");
  })();

  // =========================================================
  // 2) MODAL TP2B: Construcción del contenido + Slick dentro de modal
  // =========================================================
  (function initTp2bModal() {
    const modalEl = document.getElementById("tp2b-modal");
    const modalContentEl = document.getElementById("tp2b-modal-content");
    const posts = document.querySelectorAll(".tp2b-post");

    if (
      !modalEl ||
      !modalContentEl ||
      !posts.length ||
      typeof M === "undefined"
    )
      return;

    // Inicializa Materialize modal
    const modalInstance = M.Modal.init(modalEl, {
      onOpenEnd: function () {
        const $slider = $(".slider-tp2b");
        if ($slider.length && !$slider.hasClass("slick-initialized")) {
          $slider.slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            dots: false,
            fade: true,
          });
        } else if ($slider.length) {
          $slider.slick("setPosition");
        }
      },
      onCloseEnd: function () {
        const $slider = $(".slider-tp2b");
        if ($slider.length && $slider.hasClass("slick-initialized")) {
          $slider.slick("unslick");
        }
      },
    });

    posts.forEach(function (post) {
      post.addEventListener("click", function () {
        const title = post.getAttribute("data-title") || "";
        const thumbnail = post.getAttribute("data-thumbnail") || "";
        const descripcion = post.getAttribute("data-descripcion") || "";
        const logo = post.getAttribute("data-logo") || "";
        const gallery = post.getAttribute("data-gallery") || "[]";

        let galleryImages = [];
        try {
          galleryImages = JSON.parse(gallery) || [];
        } catch (e) {
          galleryImages = [];
        }

        let galleryHtml = "";
        galleryImages.forEach(function (image) {
          if (image && image.url) {
            galleryHtml += `<img src="${image.url}" alt="${title}" class="responsive-img">`;
          }
        });

        const html = `
          <div class="row">
            <div class="col s12 m6">
              <div class="slider-tp2b">
                ${thumbnail ? `<img src="${thumbnail}" alt="${title}" class="responsive-img">` : ""}
                ${galleryHtml}
              </div>
            </div>

            <div class="col s12 m6">
              <div class="cont-tp2n">
                <div class="row valign-wrapper">
                  <div class="col s2">${logo ? `<img src="${logo}" alt="${title}" class="logo-img">` : ""}</div>
                  <div class="col s1"></div>
                  <div class="col s9"><h4>${title}</h4></div>
                </div>

                <p>${descripcion}</p>
                <hr>
                <br>

                <div class="hotel-rel">
                  <div class="row valign-wrapper">
                    <div class="col s3 center-align"><i class="fa-solid fa-location-dot"></i></div>
                    <div class="col s7">
                      <label>Colombia, Bogota</label>
                      <h5>JW Marriott Bogotá</h5>
                    </div>
                    <div class="col s2"></div>
                  </div>
                </div>

                <div class="hotel-rel">
                  <div class="row valign-wrapper">
                    <div class="col s3 center-align"><i class="fa-solid fa-location-dot"></i></div>
                    <div class="col s7">
                      <label>Colombia, Bogota</label>
                      <h5>JW Marriott Bogotá</h5>
                    </div>
                    <div class="col s2"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        `;

        modalContentEl.innerHTML = html;
        modalInstance.open();
      });
    });
  })();

  // =========================================================
  // 3) Deck cards (lo tuyo, igual, pero aislado)
  // =========================================================
  (function initDeckCards() {
    function extractImageSources(root) {
      const imgs = root.querySelectorAll("img");
      const srcs = [];
      imgs.forEach((img) => {
        const src = img.currentSrc || img.getAttribute("src");
        if (src && !srcs.includes(src)) srcs.push(src);
      });
      return srcs;
    }

    function buildDeck(root, srcs) {
      if (root.querySelector(":scope > .deck-cards")) return null;

      const deck = document.createElement("div");
      deck.className = "deck-cards";

      srcs.forEach((src, idx) => {
        const card = document.createElement("div");
        card.className = "deck-card";
        card.dataset.index = idx;

        const img = document.createElement("img");
        img.src = src;
        img.alt = "";

        card.appendChild(img);
        deck.appendChild(card);
      });

      root.appendChild(deck);
      return deck;
    }

    function applyClasses(deck) {
      const cards = Array.from(deck.querySelectorAll(".deck-card"));
      cards.forEach((c) =>
        c.classList.remove(
          "is-1",
          "is-2",
          "is-3",
          "is-4",
          "is-hidden",
          "is-out",
        ),
      );

      if (cards[0]) cards[0].classList.add("is-1");
      if (cards[1]) cards[1].classList.add("is-2");
      if (cards[2]) cards[2].classList.add("is-3");
      if (cards[3]) cards[3].classList.add("is-4");
      for (let i = 4; i < cards.length; i++)
        cards[i].classList.add("is-hidden");
    }

    function animateDeck(deck) {
      if (deck.dataset.running === "1") return;
      deck.dataset.running = "1";

      applyClasses(deck);

      setInterval(() => {
        const first = deck.querySelector(".deck-card");
        if (!first) return;

        first.classList.add("is-out");
        setTimeout(() => {
          first.classList.remove("is-out");
          deck.appendChild(first);
          applyClasses(deck);
        }, 650);
      }, 5000);
    }

    function init() {
      document.querySelectorAll(".deck-source").forEach((root) => {
        if (root.dataset.deckInit === "1") return;

        const srcs = extractImageSources(root);
        if (!srcs || srcs.length < 2) return;

        const deck = buildDeck(root, srcs);
        if (!deck) return;

        root.dataset.deckInit = "1";
        animateDeck(deck);
      });
    }

    init();
    let tries = 0;
    const t = setInterval(() => {
      init();
      tries++;
      if (tries > 20) clearInterval(t);
    }, 400);
  })();

  // =========================================================
  // 4) More info slider (tu bloque, sin cambios, pero ordenado)
  // =========================================================
  (function initMoreInfo() {
    const $more = $(".cont-more-info");
    if (!$more.length) return;

    if (!$more.hasClass("slick-initialized")) {
      $more.slick({
        infinite: false,
        arrows: false,
        dots: false,
        variableHeight: true,
        fade: true,
        swipe: false,
        autoplay: false,
      });
    }

    $(".actionbar")
      .not(".bar4")
      .on("click", function () {
        $(".more-info").addClass("open_menu");
        $(".actionbar").removeClass("active");
        $(this).addClass("active");
      });

    $(".actionbar").on("click", function () {
      $(".more-info").addClass("open_menu");
      $(".actionbar").removeClass("active");
      $(this).addClass("active");
    });

    $(".close_bar").on("click", function () {
      $(".more-info").removeClass("open_menu");
      $(".actionbar").removeClass("active");
    });

    $(".bar1").on("click", function () {
      $more.slick("slickGoTo", 0);
    });
    $(".bar2").on("click", function () {
      $more.slick("slickGoTo", 1);
    });
    $(".bar3").on("click", function () {
      $more.slick("slickGoTo", 2);
    });

    $("#toggle-bar-hotel").on("click", function () {
      $(".bar-hotels").toggleClass("bar-hotels-active");
    });
  })();
});
