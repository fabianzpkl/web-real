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