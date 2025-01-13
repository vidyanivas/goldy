(function($) {
    "use strict";

    var ptSkills = function( $scope, $ ) {
        var wrapper = $scope.find('.skill-item');

        if ( ! wrapper.length ) {
            return false;
        }
        
        wrapper.each( function() {
            jQuery(this).find('.chart').addClass('animated');
        });
    }

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/yprm_skills.default', ptSkills );
    });

})(jQuery);