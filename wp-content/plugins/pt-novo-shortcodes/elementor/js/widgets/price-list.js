(function($) {
    "use strict";

    var ptPriceList = function( $scope, $ ) {

        var price_list = $scope.find('.price-list.row'),
            settings = price_list.data('banner-settings');

        if ( ! price_list.length ) {
            return false;
        }

        if ( price_list.find('.item').length > 1 && settings.carousel ) {
            price_list.addClass('owl-carousel').owlCarousel({
                loop:true,
                items:1,
                nav: settings.arrows,
                dots: false,
                autoplay: settings.autoplay,
                autoplayTimeout: settings.autoplay_speed,
                autoplayHoverPause: settings.pauseohover,
                smartSpeed: settings.speed,
                navClass: ['owl-prev basic-ui-icon-left-arrow','owl-next basic-ui-icon-right-arrow'],
                navText: false,
                responsive:{
                    0:{
                        nav: false,
                        items: 1,
                    },
                    768:{
                        nav: settings.arrows,
                        items: settings.mobile_cols,
                    },
                    980:{
                        items: settings.tablet_cols,
                    },
                    1200:{
                        items: settings.desctop_cols,
                    },
                },
            });
		}

            
                price_list.on('click', '.options .button-style1', function( e ) {
                    e.stopPropagation();
					console.log(jQuery(this).parent())
                    if ( jQuery(this).parent().hasClass('active') ) {
                        jQuery(this).removeClass('active').parent().removeClass('active').find('.wrap').slideUp();
                    } else {
                        jQuery(this).addClass('active').parent().addClass('active').find('.wrap').slideDown();
                    }
                    return false;
                });
            
        

        jQuery(window).trigger('resize').trigger('scroll');
    }

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/yprm_price_list.default', ptPriceList );
    });

})(jQuery);