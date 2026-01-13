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
  document.querySelectorAll(".stacked-cards").forEach((stack) => {
    const list =
      stack.querySelector(".elementor-image-gallery") ||
      stack.querySelector(".gallery");
    if (!list) return;

    setInterval(() => {
      const first = list.children[0];
      if (first) list.appendChild(first);
    }, 2500);
  });
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
