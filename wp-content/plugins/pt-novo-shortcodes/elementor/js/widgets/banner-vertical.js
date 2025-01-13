(function($) {
    "use strict";

    var pt_banner_vertical = function( $scope, $ ) {
        var vertical_banner =  $scope.find('.vertical-banner.vertical-parallax-area');

        if ( ! vertical_banner.length ) {
            return false;
        }

        vertical_banner.find('.vertical-parallax-slider').each( function() {

                if ( jQuery('.header-space:not(.hide)').length ) {
                    jQuery(this).css('height', jQuery(window).outerHeight() - (jQuery('.header-space:not(.hide)').outerHeight() || 0) - (jQuery('#wpadminbar').outerHeight() || 0) );
                    jQuery(this).find('.cell').css('height', jQuery(this).height());
                } else {
                    jQuery(this).css('height', jQuery(window).outerHeight() - (jQuery('#wpadminbar').outerHeight() || 0) );
                    jQuery(this).find('.cell').css('height', jQuery(this).height());
                }

 
        });
        
        vertical_banner.find('.vertical-parallax-slider').each(function () {
            jQuery('body').addClass('body-one-screen');

            var this_el = jQuery(this),
                el = this_el.find('.item'),
                delay = 800,
                dots = this_el.parent().find('.pagination-dots'),
                nav = this_el.parent().find('.nav-arrows'),
                status = false;

            el.each(function () {
                jQuery(this).css('z-index', parseInt(el.length - jQuery(this).index()));
                dots.append('<span></span>');
            });

            function vertical_parallax(coef, index) {
                index = index === undefined ? false : index;
                if (coef != false) {
                    var index = this_el.find('.item.active').index() - coef;
                }
                el.eq(index).removeClass('prev next').addClass('active').siblings().removeClass('active');
                el.eq(index).prevAll().removeClass('next').addClass('prev');
                el.eq(index).nextAll().removeClass('prev').addClass('next');
                dots.find('span').eq(index).addClass('active').siblings().removeClass('active');
            }

            vertical_parallax(false, 0);

            this_el.on('mousewheel wheel', function (e) {
                if (jQuery(window).width() > 992) {
                    e.preventDefault();
                    var cur = this_el.find('.item.active').index();
                    if (status != true) {
                        status = true;
                        if (e.originalEvent.deltaY > 0 && cur != parseInt(el.length - 1)) {
                            vertical_parallax('-1');
                            setTimeout(function () {
                                status = false
                            }, delay);
                        } else if (e.originalEvent.deltaY < 0 && cur != 0) {
                            vertical_parallax('1');
                            setTimeout(function () {
                                status = false
                            }, delay);
                        } else {
                            status = false;
                        }
                    }
                }
            });

            dots.on('click', 'span:not(.active)', function () {
                jQuery(this).addClass('active').siblings().removeClass('active');
                vertical_parallax(false, jQuery(this).index());
            });

            nav.on('click', '.prev', function () {
                var cur = this_el.find('.item.active').index();
                if (cur != parseInt(el.length - 1)) {
                    vertical_parallax('-1');
                }
            }).on('click', '.next', function () {
                var cur = this_el.find('.item.active').index();
                if (cur != 0) {
                    vertical_parallax('1');
                }
            });
        });
    }

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/yprm_banner_vertical.default', pt_banner_vertical );
    });

})(jQuery);