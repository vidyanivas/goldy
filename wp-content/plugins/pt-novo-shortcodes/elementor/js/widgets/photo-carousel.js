(function($) {
    "use strict";

    var yprmPhotoCarousel = function($scope, $) {

        var photo_carousel = $scope.find('.photo-carousel'),
            settings = photo_carousel.data('banner-settings');

        if (!photo_carousel.length) {
            return false;
        }

       
        var items = photo_carousel.find('.carousel').find('.item');
        var loopMode = items.length > 1; 

        if (!loopMode) {
            console.warn("Swiper Loop Warning: Not enough slides for loop mode. Loop has been disabled.");
        }

        if (items.length > 1) {
            photo_carousel.find('.carousel').addClass('owl-carousel').owlCarousel({
                dots: settings.arrows ? settings.arrows : false,
                autoplay: settings.autoplay ? settings.autoplay : false,
                autoplayTimeout: settings.autoplay_speed ? settings.autoplay_speed : false,
                smartSpeed: settings.speed ? settings.speed : false,
                loop: loopMode, 
                items: 1,
                nav: false,
                autoWidth: false,
                navText: false,
                responsive: {
                    0: {
                        items: 2
                    },
                    480: {
                        items: 3
                    },
                    768: {
                        items: 4
                    },
                    980: {
                        items: 5
                    },
                    1200: {
                        items: 6
                    },
                    1400: {
                        items: 7
                    },
                    1700: {
                        items: 8
                    },
                    1980: {
                        items: 9
                    },
                }
            });
        }
    };

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/yprm_photo_carousel.default', yprmPhotoCarousel);
    });

})(jQuery);
