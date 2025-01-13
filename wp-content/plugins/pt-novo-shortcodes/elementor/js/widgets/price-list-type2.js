(function($) {
    "use strict";

    var Price_List_Type2 = function( $scope, $ ) {
        var $Price_List_Type2 =  $scope.find('.price-list-type2');

        if ( ! $Price_List_Type2.length ) {
            return false;
        }

        var settings = $Price_List_Type2.data('banner-settings');

        if ( ! settings.carousel ) return;

		if ( $Price_List_Type2.find('.item').length > 1 ) {
            $Price_List_Type2.addClass('owl-carousel').owlCarousel({
                loop:settings.loop ? settings.loop : false,
                items:1,
                nav: settings.arrows ? settings.arrows : false,
                dots: false,
                autoplay: settings.autoplay ? settings.autoplay : false,
                autoplayTimeout: settings.autoplay_speed ? settings.autoplay_speed : false,
                autoplayHoverPause: settings.pauseohover,
                smartSpeed: settings.speed ? settings.speed : false,
                navClass: ['owl-prev basic-ui-icon-left-arrow','owl-next basic-ui-icon-right-arrow'],
                navText: false,
                responsive:{
					0:{
						nav: false,
						items: 1,
					},
					576:{
						items: settings.cols_xs ? settings.cols_xs : 1,
					},
					768:{
						nav: settings.arrows ? settings.arrows : false,
						items: settings.cols_sm ? settings.cols_sm : 1,
					},
					992:{
						items: settings.cols_md ? settings.cols_md : 2,
					},
					1200:{
						items: settings.cols_lg ? settings.cols_lg : 3,
					},
					1400:{
						items: settings.cols_xl ? settings.cols_xl : 3,
					},
				},
            });
        }
    }

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/pt_price_list_type2.default', Price_List_Type2 );
    });

})(jQuery);