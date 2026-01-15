document.addEventListener("DOMContentLoaded", function () {
  const filtroCategorias = document.getElementById("filtro_categorias");
  const carruselHoteles = $(".carrusel-hoteles");

  filtroCategorias.addEventListener("change", function () {
    const categoriaSeleccionada = filtroCategorias.value;

    // Mostrar u ocultar las publicaciones según la categoría seleccionada
    const hoteles = document.querySelectorAll(".card-hotel");
    hoteles.forEach(function (hotel) {
      if (
        categoriaSeleccionada === "todos" ||
        hotel.classList.contains("categoria-" + categoriaSeleccionada)
      ) {
        hotel.style.display = "inline-block";
      } else {
        hotel.style.display = "none";
      }
    });

    if (categoriaSeleccionada === "todos") {
    } else {
      carruselHoteles.slick("slickGoTo", 0);
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  var modalInstance;

  // Inicializar la modal
  var modalElems = document.querySelectorAll(".modal");
  M.Modal.init(modalElems, {
    onOpenEnd: function () {
      // Inicializar el slider después de que la modal está completamente abierta
      $(".slider-tp2b").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
      });
    },
    onCloseEnd: function () {
      // Desinicializar el slider al cerrar la modal (opcional)
      $(".slider-tp2b").slick("unslick");
    },
  });

  // Obtener la instancia de la modal
  modalInstance = M.Modal.getInstance(document.getElementById("tp2b-modal"));

  // Abrir la modal al hacer clic en el elemento .tp2b-post
  var tp2bPosts = document.querySelectorAll(".tp2b-post");
  tp2bPosts.forEach(function (post) {
    post.addEventListener("click", function () {
      modalInstance.open();
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  var tp2bPosts = document.querySelectorAll(".tp2b-post");

  tp2bPosts.forEach(function (post) {
    post.addEventListener("click", function () {
      var title = post.getAttribute("data-title");
      var thumbnail = post.getAttribute("data-thumbnail");
      var descripcion = post.getAttribute("data-descripcion");
      var logo = post.getAttribute("data-logo");
      var gallery = post.getAttribute("data-gallery");
      /*var hoteldate = post.getAttribute("data-hotel");
      objhoteldate = JSON.parse(hoteldate);*/
      // Construir la estructura de la galería
      var galleryImages = JSON.parse(gallery);
      var galleryHtml = "";

      galleryImages.forEach(function (image) {
        galleryHtml += `<img src="${image.url}" alt="${title}" class="responsive-img">`;
      });

      /*console.log(hoteldate);

      var hotelHtml = "";

      objhoteldate.forEach(function (element) {
        console.log(element.post_title);
        hotelHtml += `
          <p>${element.post_title}</p>

        `;
      });*/

      // Configurar y abrir la modal con dos columnas
      var modalContent = `
              <div class="row">
                  <div class="col s6">
                    <div class="slider-tp2b">
                      <img src="${thumbnail}" alt="${title}" class="responsive-img">
                      ${galleryHtml} 
                    </div>
                  </div>
                  <div class="col s6">
                      <div class="cont-tp2n">
                        <div class="row valign-wrapper">
                          <div class="col s2"><img src="${logo}" alt="${title}" class="logo-img"></div>
                          <div class="col s1"></div>
                          <div class="col s9"><h4>${title}</h4></div>
                        </div>
                        <p>${descripcion}</p>
                        <hr>
                        <br>
                       <div class="hotel-rel">
                        <div class="row valign-wrapper">
                          <div class="col s3 center-align">
                          <i class="fa-solid fa-location-dot"></i>
                          </div>
                          <div class="col s7">
                          <label>Colombia, Bogota</lable>
                          <h5>JW Marriott Bogotá</h5>
                          </div>
                          <div class="col s2">
                          </div>
                        </div>
                       </div>
                       <div class="hotel-rel">
                        <div class="row valign-wrapper">
                          <div class="col s3 center-align">
                          <i class="fa-solid fa-location-dot"></i>
                          </div>
                          <div class="col s7">
                          <label>Colombia, Bogota</lable>
                          <h5>JW Marriott Bogotá</h5>
                          </div>
                          <div class="col s2">
                          </div>
                        </div>
                       </div>
                        </div>
                    </div>
                </div>
            
                  </div>
              </div>
          `;

      M.Modal.getInstance(document.getElementById("tp2b-modal")).open();
      document.getElementById("tp2b-modal-content").innerHTML = modalContent;
    });
  });
});

document.addEventListener("DOMContentLoaded", () => {
  function extractImageSources(root) {
    // Busca imágenes dentro del widget (carrusel o galería)
    const imgs = root.querySelectorAll("img");
    const srcs = [];

    imgs.forEach((img) => {
      const src = img.currentSrc || img.getAttribute("src");
      if (!src) return;
      // Evita duplicados típicos (srcset / clones)
      if (!srcs.includes(src)) srcs.push(src);
    });

    return srcs;
  }

  function buildDeck(root, srcs) {
    // Si ya lo creamos, no lo duplicamos
    if (root.querySelector(":scope > .deck-cards")) return;

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
      c.classList.remove("is-1", "is-2", "is-3", "is-4", "is-hidden", "is-out")
    );

    if (cards[0]) cards[0].classList.add("is-1");
    if (cards[1]) cards[1].classList.add("is-2");
    if (cards[2]) cards[2].classList.add("is-3");
    if (cards[3]) cards[3].classList.add("is-4");

    for (let i = 4; i < cards.length; i++) cards[i].classList.add("is-hidden");
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
        deck.appendChild(first); // la primera pasa al final: efecto naipes
        applyClasses(deck);
      }, 650);
    }, 5000);
  }

  function init() {
    document.querySelectorAll(".deck-source").forEach((root) => {
      if (root.dataset.deckInit === "1") return;

      const srcs = extractImageSources(root);
      if (!srcs || srcs.length < 2) return; // si aún no cargó Elementor, reintentamos

      const deck = buildDeck(root, srcs);
      if (!deck) return;

      root.dataset.deckInit = "1";
      animateDeck(deck);
    });
  }

  // Primer intento
  init();

  // Elementor a veces “pinta” tarde: reintenta varias veces
  let tries = 0;
  const t = setInterval(() => {
    init();
    tries++;
    if (tries > 20) clearInterval(t);
  }, 400);
});

$(document).ready(function () {
  $(".carrusel-hoteles").slick({
    arrows: false,
    dots: false,
    infinite: false,
    slidesToShow: 3,
    slidesToScroll: 1,
    autoplay: true,
    pauseOnFocus: true,
    pauseOnHover: true,
    variableWidth: true,
    centeredSlides: true,
    swipe: true,
    breakpoints: {
      0: { slidesPerView: 1 },
      768: { slidesPerView: 2 },
      1024: { slidesPerView: 3 },
    },
  });
  $(".cont-more-info").slick({
    infinite: false,
    arrows: false,
    dots: false,
    variableHeight: true,
    fade: true,
    swipe: false,
    autoplay: false,
  });

  $(".actionbar").click(function () {
    if (!$(".more-info").hasClass("open_menu")) {
      $(".more-info").addClass("open_menu");
    }
  });

  $(".close_bar").click(function () {
    $(".more-info").removeClass("open_menu");
    $(".actionbar").removeClass("active");
  });

  $(".bar1").click(function () {
    $(".cont-more-info").slick("slickGoTo", 0);
  });

  $(".bar2").click(function () {
    $(".cont-more-info").slick("slickGoTo", 1);
  });

  $(".bar3").click(function () {
    $(".cont-more-info").slick("slickGoTo", 2);
  });

  $(".actionbar").click(function () {
    $(".actionbar").removeClass("active");
    $(this).addClass("active");
  });

  $("#toggle-bar-hotel").click(function () {
    $(".bar-hotels").toggleClass("bar-hotels-active");
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const wrap = document.querySelector(".carrusel-hoteles");
  const buttons = document.querySelectorAll(".filter-tags-hotels .tag-hotel");

  if (!wrap || !buttons.length) return;

  const cards = wrap.querySelectorAll(".card-hotel");

  function setActive(btn) {
    buttons.forEach((b) => b.classList.remove("is-active"));
    btn.classList.add("is-active");
  }

  function filterCards(filter) {
    cards.forEach((card) => {
      if (filter === "all") {
        card.style.display = "";
        return;
      }
      card.style.display = card.classList.contains(filter) ? "" : "none";
    });
  }

  buttons.forEach((btn) => {
    btn.addEventListener("click", () => {
      const filter = btn.getAttribute("data-filter");
      setActive(btn);
      filterCards(filter);
    });
  });
});
