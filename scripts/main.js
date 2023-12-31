document.addEventListener('DOMContentLoaded', function () {
    const filtroCategorias = document.getElementById('filtro_categorias');
    const carruselHoteles = $('.carrusel-hoteles');

    filtroCategorias.addEventListener('change', function () {
        const categoriaSeleccionada = filtroCategorias.value;

        // Mostrar u ocultar las publicaciones según la categoría seleccionada
        const hoteles = document.querySelectorAll('.card-hotel');
        hoteles.forEach(function (hotel) {
            if (categoriaSeleccionada === 'todos' || hotel.classList.contains('categoria-' + categoriaSeleccionada)) {
                hotel.style.display = 'inline-block';
            } else {
                hotel.style.display = 'none';
            }
        });

        if (categoriaSeleccionada === 'todos') {
        } else {
            carruselHoteles.slick('slickGoTo', 0);
        }
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
        if (!$('.more-info').hasClass('open_menu')) {
            $('.more-info').addClass('open_menu');
        }
    });

    $(".close_bar").click(function () {
        $('.more-info').removeClass('open_menu');
        $('.actionbar').removeClass('active');
    });

    $('.bar1').click(function () {
        $('.cont-more-info').slick('slickGoTo', 0);
    });

    $('.bar2').click(function () {
        $('.cont-more-info').slick('slickGoTo', 1);
    });

    $('.bar3').click(function () {
        $('.cont-more-info').slick('slickGoTo', 2);
    });

    $('.actionbar').click(function () {
        $('.actionbar').removeClass('active');
        $(this).addClass('active');
    });
});


