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



document.addEventListener("DOMContentLoaded", function() {
  // Obtén todos los radios dentro del slider
  var radios = document.querySelectorAll('#slider input[type="radio"]');
  
  // Inicializa el índice actual
  var currentIndex = 0;

  // Configura el intervalo para cambiar los radios cada 5 segundos
  var interval = setInterval(function() {
    // Desmarca el radio actual
    radios[currentIndex].checked = false;

    // Incrementa el índice o vuelve al primero si es el último
    currentIndex = (currentIndex + 1) % radios.length;

    // Marca el nuevo radio
    radios[currentIndex].checked = true;
  }, 5000);
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

  $("#toggle-bar-hotel").click(function() {
    $(".bar-hotels").toggleClass("bar-hotels-active");
  });
});
