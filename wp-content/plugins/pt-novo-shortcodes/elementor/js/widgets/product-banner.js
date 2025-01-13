(function($) {
    "use strict";

    var product_banner = function ( $scope, $ ) {

        var banner =  $scope.find('.banner.banner-items'),
            banner_categories = $scope.find('.banner-wrapper'),
            circle_nav = $scope.find('.banner-circle-nav');

        if ( ! banner.length ) {
            return false;
        }

        $('#'+banner.attr('id') ).each(function () {
            jQuery(this).on('initialize.owl.carousel', function (property) {
                jQuery(this).find('.item').each(function () {
                    var num = leadZero(jQuery(this).index() + 1);
                    jQuery(this).find('.num').text(num);
                });
            });

            var head_slider = jQuery(this),
                slider_items = head_slider.find('.item'),
                settings = head_slider.data('banner-settings');

            if ( banner_categories.find('.banner-categories').length ) {
            if ( circle_nav.length ) {
                slider_items.each(function(index) {
                    circle_nav.append('<div class="item"><svg viewBox="0 0 34 34" version="1.1" xmlns="http://www.w3.org/2000/svg"><circle cx="17" cy="17" r="16"/></svg>'+leadZero(index+1)+'</div>')
                });

                head_slider.on('changed.owl.carousel', function(e){
                    var current = e.item.index - (e.relatedTarget._clones.length / 2);
                    if(slider_items.length == current) {
                        current = 0;
                    }

                    circle_nav.find('.item').eq(current).removeClass('active prev').addClass('active').nextAll().removeClass('active prev');
                    circle_nav.find('.item').eq(current).prevAll().addClass('active prev');
                });

                circle_nav.on("click", ".item", function() {
                    var index = jQuery(this).index();
                    head_slider.trigger("to.owl.carousel", index);
                });
            }

            banner_categories.find('.banner-categories').each( function() {
                jQuery(this).on('initialize.owl.carousel', function( property ) {
                    jQuery(this).find('.item').each(function () {
                        var num = leadZero( jQuery(this).index() + 1 );
                        jQuery(this).find('.num').text(num);
                    });
                });

                if ( jQuery(this).find('.item').length > 1 ) {
                    if ( jQuery(this).find('.item').length > 4 ) {
                        var count = 4;
                        var mob = 2;
                        var table = 3;
                    } else {
                        var count = jQuery(this).find('.item').length;
                        var mob = 2;
                        var table = 2;
                    }

                    jQuery(this).addClass('owl-carousel').owlCarousel({
                        loop: true,
                        items: 1,
                        nav: true,
                        dots: false,
                        autoplay: false,
                        navClass: ['owl-prev basic-ui-icon-left-arrow', 'owl-next basic-ui-icon-right-arrow'],
                        navText: false,
                        responsive: {
                            0: {
                                nav: false,
                            },
                            480: {
                                items: 1
                            },
                            768: {
                                nav: true,
                                items: mob
                            },
                            980: {
                                items: table
                            },
                            1200: {
                                items: count
                            },
                        },
                    });
                }
            });
        }

            if (head_slider.find('.item').length > 1) {
                head_slider.addClass('owl-carousel').owlCarousel({
                    loop: settings.loop,
                    autoplay: settings.autoplay,
                    dots: settings.dots,
                    nav: settings.arrows,
                    autoplayTimeout: settings.autoplay_speed,
                    autoplayHoverPause: settings.pauseohover,
                    smartSpeed: settings.speed,
                    animateIn: settings.animateIn ? settings.animateIn : '',
                    animateOut: settings.animateOut ? settings.animateOut : '',
                    items: 1,
                    navClass: ['owl-prev basic-ui-icon-left-arrow', 'owl-next basic-ui-icon-right-arrow'],
                    navText: false,
                    responsive: {
                        0: {
                            nav: false,
                        },
                        768: {
                            nav: settings.arrows ? settings.arrows : false,
                        },
                        
                    },
                });
            }
        });
    
        $(window).trigger('resize');
    
        
            banner_categories.find('.banner-right-buttons div.category').on('click', function ( e ) {
                e.preventDefault();
                e.stopPropagation();

                if ( jQuery(this).hasClass('active') ) {
                    jQuery(this).parents('.banner-area').find('.banner-categories').removeClass('active');
                    jQuery(this).removeClass('active');
                } else {
                    jQuery(this).parents('.banner-area').find('.banner-categories').addClass('active');
                    jQuery(this).addClass('active').siblings().removeClass('active');
                    jQuery(this).parents('.banner-area').find('.banner-about').removeClass('active');
                }
            });

            banner_categories.find('.banner-right-buttons div.about').on('click', function ( e ) {
                e.preventDefault();
                e.stopPropagation();
                if (jQuery(this).hasClass('active')) {
                    jQuery(this).parents('.banner-area').find('.banner-about').removeClass('active');
                    jQuery(this).removeClass('active');
                } else {
                    jQuery(this).parents('.banner-area').find('.banner-about').addClass('active');
                    jQuery(this).addClass('active').siblings().removeClass('active');
                    jQuery(this).parents('.banner-area').find('.banner-categories').removeClass('active');
                }
            });
        

        banner.find('.words').each(function () {
            var typed2 = new Typed(this, {
                strings: jQuery(this).attr('data-array').split(','),
                typeSpeed: 100,
                backSpeed: 0,
                fadeOut: true,
                loop: true
            });
        });

        jQuery(window).on("load resize", function () {
            jQuery('.header-space').css('height', (jQuery('.site-header').outerHeight() || 0) + (jQuery('.header + .navigation').outerHeight() || 0) + (jQuery('.ypromo-site-bar').outerHeight() || 0));
            
            jQuery('main.main-row').css('min-height', jQuery(window).outerHeight() - (jQuery('.site-footer').outerHeight() || 0) - jQuery('.footer-social-button').outerHeight() - (jQuery('.header-space:not(.hide)').outerHeight() || 0) - (jQuery('.ypromo-site-bar').outerHeight() || 0) - (jQuery('#wpadminbar').outerHeight() || 0));

            jQuery('.protected-post-form .cell').css('height', jQuery(window).outerHeight() - (jQuery('.site-footer').outerHeight() || 0) - jQuery('.footer-social-button').outerHeight() - (jQuery('.header-space:not(.hide)').outerHeight() || 0) - (jQuery('.ypromo-site-bar').outerHeight() || 0) - (jQuery('#wpadminbar').outerHeight() || 0));

            jQuery('.banner:not(.fixed-height)').each(function () {
                var coef = 0;
                if (jQuery(this).parents('.banner-area').hasClass('external-indent') && !jQuery(this).parents('.banner-area').hasClass('with-carousel-nav')) {
                    coef = 70;
                }
                jQuery(this).css('height', jQuery(window).outerHeight() - (jQuery('.header-space:not(.hide)').outerHeight() || 0) - (jQuery('#wpadminbar').outerHeight() || 0) - coef);
                jQuery(this).find('.cell').css('height', jQuery(this).height());
                jQuery(this).parent().find('.banner-categories .item').css('height', jQuery(this).height());
                jQuery(this).parent().find('.banner-about .cell').css('height', jQuery(this).height() - 20);
                jQuery(this).parent().find('.banner-about .image').css('height', jQuery(this).height());
                jQuery(this).parent().find('.banner-about .text').css('height', jQuery(this).height());
                jQuery(this).parent().find('.banner-right-buttons .cell').css('height', jQuery(this).height());
            });
            
            jQuery('.banner.fixed-height').each(function () {
                jQuery(this).find('.cell').css('height', jQuery(this).height());
                jQuery(this).parent().find('.banner-categories .item').css('height', jQuery(this).height());
                jQuery(this).parent().find('.banner-about .cell').css('height', jQuery(this).height() - 20);
                jQuery(this).parent().find('.banner-about .image').css('height', jQuery(this).height());
                jQuery(this).parent().find('.banner-about .text').css('height', jQuery(this).height());
                jQuery(this).parent().find('.banner-right-buttons .cell').css('height', jQuery(this).height());
            });

            jQuery('.full-screen-nav .cell').css('height', jQuery(window).height() - 20 - (jQuery('#wpadminbar').height() || 0));
 

            jQuery('.side-header .cell').css('height', jQuery('.side-header .wrap').height());

            if (jQuery(window).width() <= '768') {
                jQuery('body').addClass('is-mobile-body');
            } else {
                jQuery('body').removeClass('is-mobile-body');
            }

            jQuery('.category-slider-area').each(function () {
                var this_el = jQuery(this);
                this_el.css('height', jQuery(window).height() - (jQuery('#wpadminbar').outerHeight() || 0));
                this_el.find('.category-slider-images').css('height', this_el.height());
                this_el.find('.cell').css('height', this_el.height());
            });
        });
    }

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/yprm_product_banner.default', product_banner );
    });

})(jQuery);