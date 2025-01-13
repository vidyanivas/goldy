(function($) {
    "use strict";

    var ptTeam = function( $scope, $ ) {
        var wrapper = $scope.find('.team-items.team-carousel'),
            settings = wrapper.data('banner-settings');

        if ( ! wrapper.length ) {
            return false;
        }

        if ( wrapper.find('.item').length > 1 ) {
            wrapper.addClass('owl-carousel').owlCarousel({
                loop: settings.loop ? settings.loop : false,
                autoplay: settings.autoplay ? settings.autoplay : false,
                nav: settings.arrows ? settings.arrows : false,
                dots: settings.dots ? settings.dots : false,
                autoplayHoverPause: settings.pause_on_hover ? settings.pause_on_hover : false,
                autoplayTimeout: settings.autoplay_speed ? settings.autoplay_speed : false,
                smartSpeed: settings.speed ? settings.speed : false,
                items:1,
                navClass: ['owl-prev basic-ui-icon-left-arrow','owl-next basic-ui-icon-right-arrow'],
                navText: false,
                margin: 30,
                responsive:{
                    0:{
                        nav: false,
                        items: 1,
                    },
                    768:{
                        nav: settings.arrows ? settings.arrows : false,
                        items: settings.mobile_columns,
                    },
                    980:{
                        nav: settings.arrows ? settings.arrows : false,
                        items: settings.tablet_columns,
                    },
                    1200:{
                        nav: settings.arrows ? settings.arrows : false,
                        items: settings.desktop_columns,
                    },
                },
            });
        }
    }

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/yprm_team.default', ptTeam );
    });

})(jQuery);