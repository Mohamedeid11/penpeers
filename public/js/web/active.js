

(function($) {
    'use strict';
/*============ Scroll Up Activation ============*/
    $.scrollUp({
        scrollText: '<i class="fa fa-angle-up"></i>',
        easingType: 'linear',
        scrollSpeed: 900,
        animation: 'slide'
    });

/*=========== Mobile Menu ===========*/
    $('nav.mobilemenu__nav').meanmenu({
        meanMenuClose: 'X',
        meanMenuCloseSize: '18px',
        meanScreenWidth: '1200',
        meanExpandableChildren: true,
        meanMenuContainer: '.mobile-menu',
        onePage: true
    });

/*=========== Wow Active ===========*/
    new WOW().init();

/*=========== Sticky Header ===========*/
    function stickyHeader() {
        $(window).on('scroll', function () {
            var sticky_menu = $('.sticky__header');
            var pos = sticky_menu.position();
            if (sticky_menu.length) {
                $(window).on('scroll', function () {
                    if(document.documentElement.offsetHeight - document.documentElement.clientHeight > 40) {
                        var windowpos = $(window).scrollTop();
                        if (windowpos > pos.top && windowpos) {
                            sticky_menu.addClass('is-sticky');
                        } else {
                            sticky_menu.removeClass('is-sticky');
                        }
                    }
                });
            }
        });
    }
    stickyHeader();

/*=============  Slider Activation  ==============*/
    $('.slide__activation').owlCarousel({
        loop:true,
        margin: 0,
        nav:true,
        autoplay: true,
        autoplayTimeout: 5000,
        items:1,
        rtl: lang === "ar" ? true : false,
        navText: ['<i class="zmdi zmdi-chevron-left"></i>', '<i class="zmdi zmdi-chevron-right"></i>' ],
        dots: false,
        lazyLoad: true,
        responsive:{
        0:{
          items:1
        },
        1920:{
          items:1
        }
        }
    });

/*====== Dropdown ======*/
    $('.dropdown').parent('.drop').css('position' , 'relative');
})(jQuery);

