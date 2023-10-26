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
});