(function ($) {

    "use strict";


    /*-------------------------------------

     Carousel slider initiation

     -------------------------------------*/

    $('.gym-carousel').each(function () {

        var carousel = $(this),

            loop = carousel.data('loop'),

            items = carousel.data('items'),

            margin = carousel.data('margin'),

            stagePadding = carousel.data('stage-padding'),

            autoplay = carousel.data('autoplay'),

            autoplayTimeout = carousel.data('autoplay-timeout'),

            smartSpeed = carousel.data('smart-speed'),

            dots = carousel.data('dots'),

            nav = carousel.data('nav'),

            navSpeed = carousel.data('nav-speed'),

            rXsmall = carousel.data('r-x-small'),

            rXsmallNav = carousel.data('r-x-small-nav'),

            rXsmallDots = carousel.data('r-x-small-dots'),

            rXmedium = carousel.data('r-x-medium'),

            rXmediumNav = carousel.data('r-x-medium-nav'),

            rXmediumDots = carousel.data('r-x-medium-dots'),

            rSmall = carousel.data('r-small'),

            rSmallNav = carousel.data('r-small-nav'),

            rSmallDots = carousel.data('r-small-dots'),

            rMedium = carousel.data('r-medium'),

            rMediumNav = carousel.data('r-medium-nav'),

            rMediumDots = carousel.data('r-medium-dots'),

            rLarge = carousel.data('r-large'),

            rLargeNav = carousel.data('r-large-nav'),

            rLargeDots = carousel.data('r-large-dots'),

            center = carousel.data('center');


        carousel.owlCarousel({

            loop: (loop ? true : false),

            items: (items ? items : 4),

            lazyLoad: true,

            margin: (margin ? margin : 0),

            autoplay: (autoplay ? true : false),

            autoplayTimeout: (autoplayTimeout ? autoplayTimeout : 1000),

            smartSpeed: (smartSpeed ? smartSpeed : 250),

            dots: (dots ? true : false),

            nav: (nav ? true : false),

            navText: ["<i class='fa fa-angle-left' aria-hidden='true'></i>", "<i class='fa fa-angle-right' aria-hidden='true'></i>"],

            navSpeed: (navSpeed ? true : false),

            center: (center ? true : false),

            responsiveClass: true,

            responsive: {

                0: {

                    items: (rXsmall ? rXsmall : 1),

                    nav: (rXsmallNav ? true : false),

                    dots: (rXsmallDots ? true : false)

                },

                480: {

                    items: (rXmedium ? rXmedium : 2),

                    nav: (rXmediumNav ? true : false),

                    dots: (rXmediumDots ? true : false)

                },

                768: {

                    items: (rSmall ? rSmall : 3),

                    nav: (rSmallNav ? true : false),

                    dots: (rSmallDots ? true : false)

                },

                992: {

                    items: (rMedium ? rMedium : 5),

                    nav: (rMediumNav ? true : false),

                    dots: (rMediumDots ? true : false)

                },

                1199: {

                    items: (rLarge ? rLarge : 6),

                    nav: (rLargeNav ? true : false),

                    dots: (rLargeDots ? true : false)

                }

            }

        });


    });


    /*---------------------------

     Scroll to top

     ----------------------------*/

    $(window).scroll(function () {

        if ($(this).scrollTop() > 100) {

            $('.scrollToTop').fadeIn();

        } else {

            $('.scrollToTop').fadeOut();

        }

    });


    //Click event to scroll to top

    $('.scrollToTop').on('click', function () {

        $('html, body').animate({scrollTop: 0}, 800);

        return false;

    });


    /*-------------------------------------

    Jquery Mobile menu link

    -----------------------------------*/

    $('nav#dropdown').meanmenu({

        siteLogo: "<a href='index.html'><img src='img/mobile-logo.png' /></a>"

    });

  
    new WOW().init();


    /*----------------------------

      Product Count added spinner

    ------------------------------ */

    $('.spinner .btn:first-of-type').on('click', function () {

        $('.spinner input').val(parseInt($('.spinner input').val(), 10) + 1);

    });

    $('.spinner .btn:last-of-type').on('click', function () {

        $('.spinner input').val(parseInt($('.spinner input').val(), 10) - 1);

    });


    /*-------------------------------------

      jQuery Search Box customization

    -------------------------------------*/

    $(".header-top-search.search-box").on('click', '.search-button', function (event) {

        event.preventDefault();

        var v = $(this).prev('.search-text');

        if (v.hasClass('active')) {

            v.removeClass('active');

        } else {

            v.addClass('active');

        }

        return false;

    });



    /*-------------------------------------

    Accordion

    -------------------------------------*/

    var accordion = $('#accordion');

    accordion.children('.panel').children('.panel-collapse').each(function () {

        if ($(this).hasClass('in')) {

            $(this).parent('.panel').children('.panel-heading').addClass('active');

        }

    });

    accordion

        .on('show.bs.collapse', function (e) {

            $(e.target).prev('.panel-heading').addClass('active');

        })

        .on('hide.bs.collapse', function (e) {

            $(e.target).prev('.panel-heading').removeClass('active');

        });


    /*-------------------------------------

    Jquery Fixed Header Menu

    -----------------------------------*/

    $(window).scroll(function () {

        var s = $("#sticker"),

            w = $(".wrapper"),

            windowpos = $(window).scrollTop(),

            windowWidth = $(window).width(), type, topBarH;

        if (s.hasClass("header-style4")) {

            type = "h4";

        } else if (s.hasClass("header-style3")) {

            type = "h3";

        } else if (s.hasClass("header-style2")) {

            type = "h2";

        } else if (s.hasClass("header-style1")) {

            type = "h1";

        }


        if (windowWidth > 767) {

            if (type === "h4" || type === "h3" || type === "h2") {

                topBarH = s.find('.header-top-bar').outerHeight();

                if (windowpos <= topBarH) {

                    s.css('top', '-' + windowpos + 'px');

                }

            } else {

                topBarH = 1;

            }

            console.log(topBarH);

            if (windowpos >= topBarH) {

                s.addClass('stick');

                if (type === "h4" || type === "h3" || type === "h2") {

                    s.css('top', '-' + topBarH + 'px');

                }

            } else {

                s.removeClass('stick');

            }


        }else {
            if($(".mean-bar .meanmenu-reveal").hasClass('meanclose')){
                $(".mean-bar .meanmenu-reveal").trigger('click');
            }
        }
    });


    /*-------------------------------------

     Window onLoad and onResize event trigger

     -------------------------------------*/

    $(window).on('load resize', function () {

        //Define the maximum height for mobile menu

        /* Active this code for set mobile menu height
        var wHeight = $(window).height(),

            mLogoH = $('a.logo-mobile-menu').outerHeight();

        wHeight = wHeight + mLogoH + 50;

        $('.mean-nav > ul').css('height', wHeight + 'px');
        */

        // Page Preloader

        $('#preloader').fadeOut('slow', function () {

            $(this).remove();

        });

    });


    var currHeight;

    function HiddenResize1() {

        $('.nivoSlider').css({overflow: 'visible', background: 'none'});

        setTimeout(function () {

            $('.nivo-main-image').stop().animate({opacity: 0}, 500);

            currHeight = $('.nivoSlider').data('nivo:vars').currentImage.height();

        }, 10);

    }

    function HiddenResize2() {

        $('.nivo-main-image').css({opacity: 1, height: currHeight});

    }


})(jQuery);