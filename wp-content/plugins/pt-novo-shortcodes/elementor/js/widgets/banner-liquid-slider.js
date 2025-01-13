(function($) {
    "use strict";

    var yprm_banner_liquid_slider = function( $scope, $ ) {
        var liquiq_banner =  $scope.find('.liquiq-banner .banner-item');

        if ( ! liquiq_banner.length ) {
            return false;
        }

        var wrapper = $scope.find('.liquiq-banner'),
            settings = jQuery( wrapper ).data('banner-settings');

        new LiquidSlider(document.querySelector('.liquiq-banner'), settings);
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/yprm_banner_liquid_slider.default', yprm_banner_liquid_slider );
    });

})(jQuery);