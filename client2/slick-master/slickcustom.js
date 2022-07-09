$(document).ready(function() {
    // $('.demo').slick();
    $('.slick-carousel').slick({
        dots: false,
        slidesToShow: 4,
        slidesToScroll: 1,
        touchMove: false

    });

    $('.slick-carousel').on('swipe', function(event, slick, direction) {
        console.log(direction);
        // leftslickcustom
    });

    $('.slick-carousel2').slick({
        dots: false,
        slidesToShow: 2,
        slidesToScroll: 2,
        touchMove: false
    });
    $('.slick-carousel3').slick({
        dots: true,
        slidesToShow: 5,
        slidesToScroll: 1,
        touchMove: false
    });

    $('.slick-carousel4').slick({
        dots: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        touchMove: false
    });

});