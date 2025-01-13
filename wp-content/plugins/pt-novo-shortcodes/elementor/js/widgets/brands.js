(function($) {
    "use strict";

    var Brands = function($scope, $) {
        var category_block = $scope.find('.brand-block');

        if (!category_block.length) {
            return false;
        }

        var settings = category_block.data('portfolio-settings');

        if (settings.carousel !== 'yes') return;

        let autoplay = false,
            breakpoints = {};

        if (settings.autoplay === 'yes') {
            autoplay = {
                delay: settings.autoplay_speed
            };
        }

        if (settings.cols_xs) {
            breakpoints[200] = {
                slidesPerView: settings.cols_xs
            };
        }
        if (settings.cols_sm) {
            breakpoints[576] = {
                slidesPerView: settings.cols_sm
            };
        }
        if (settings.cols_md) {
            breakpoints[768] = {
                slidesPerView: settings.cols_md
            };
        }
        if (settings.cols_lg) {
            breakpoints[992] = {
                slidesPerView: settings.cols_lg
            };
        }
        if (settings.cols_xl) {
            breakpoints[1200] = {
                slidesPerView: settings.cols_xl
            };
        }

        let swiperElement = $scope.find('.swiper').get(0);
        let slides = $scope.find('.swiper-slide');

        
        let slideCount = slides.length;
        let loopMode = slideCount > settings.cols_xl; 
        if (!loopMode) {
            console.warn("Swiper Loop Warning: Not enough slides for loop mode. Loop has been disabled.");
        }

        
        new Swiper(swiperElement, {
            loop: loopMode, 
            autoplay: autoplay,
            slidesPerView: loopMode ? settings.cols_xl : Math.min(slideCount, settings.cols_xl), 
            slidesPerGroup: loopMode ? settings.cols_xl : 1, 
            watchSlidesVisibility: true,
            loopAdditionalSlides: loopMode ? 2 : 0, 
            spaceBetween: 30,
            navigation: {
                nextEl: $scope.find('.next').get(0),
                prevEl: $scope.find('.prev').get(0),
            },
            breakpointsInverse: true,
            breakpoints: breakpoints
        });
    };

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/yprm_brands.default', Brands);
    });

})(jQuery);
