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


<script>
document.addEventListener("DOMContentLoaded", () => {
  function applyDeckClasses(list) {
    const cards = Array.from(list.children);

    cards.forEach(el => {
      el.classList.remove(
        "deck-card--top","deck-card--two","deck-card--three","deck-card--four",
        "deck-card--hidden","deck-card--out"
      );
    });

    if (cards[0]) cards[0].classList.add("deck-card--top");
    if (cards[1]) cards[1].classList.add("deck-card--two");
    if (cards[2]) cards[2].classList.add("deck-card--three");
    if (cards[3]) cards[3].classList.add("deck-card--four");

    for (let i = 4; i < cards.length; i++) {
      cards[i].classList.add("deck-card--hidden");
    }
  }

  function initDeck(deckRoot) {
    if (deckRoot.dataset.deckInit === "1") return;

    const list =
      deckRoot.querySelector(".swiper-wrapper") ||
      deckRoot.querySelector(".slick-track");

    if (!list || list.children.length < 2) return;

    deckRoot.dataset.deckInit = "1";
    applyDeckClasses(list);

    const interval = setInterval(() => {
      const first = list.children[0];
      if (!first) return;

      first.classList.add("deck-card--out");

      setTimeout(() => {
        first.classList.remove("deck-card--out");
        list.appendChild(first);      // la manda al final (pasa atrás)
        applyDeckClasses(list);       // re-apila
      }, 650);

    }, 2200);

    // opcional: guarda interval por si quieres pararlo luego
    deckRoot.dataset.deckInterval = interval;
  }

  function scan() {
    document.querySelectorAll(".deck-cards").forEach(initDeck);
  }

  scan();

  // Elementor a veces renderiza tarde, reintenta un poco
  let tries = 0;
  const t = setInterval(() => {
    scan();
    tries++;
    if (tries > 12) clearInterval(t);
  }, 500);
});
</script>




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
    swipe: true,
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
