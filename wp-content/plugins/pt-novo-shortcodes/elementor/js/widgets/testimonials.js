(function ($) {
  "use strict";

  var PTTestimonials = function ($scope, $) {
    var testimonials_block = $scope.find('.testimonials.row');

    if (!testimonials_block.length) {
      return false;
    }

    var settings = testimonials_block.data('portfolio-settings');

    if (testimonials_block.find('.testimonial-item').length > 1) {
      testimonials_block.owlCarousel({
        loop: true,
        items: 1,
        dots: settings.arrows ? settings.arrows : false,
        nav: false,
        autoplay: settings.autoplay ? settings.autoplay : false,
        autoplayTimeout: settings.autoplay_speed ? settings.autoplay_speed : false,
        autoplayHoverPause: settings.pauseohover,
        smartSpeed: settings.speed ? settings.speed : false,
        navClass: ['owl-prev basic-ui-icon-left-arrow', 'owl-next basic-ui-icon-right-arrow'],
        navText: false,
        responsive: {
          0: {
            items: settings.cols_xs ? settings.cols_xs : 1,
            nav: settings.arrows ? settings.arrows : false,
          },
          576: {
            items: settings.cols_sm ? settings.cols_sm : 1,
            nav: settings.arrows ? settings.arrows : false,
          },
          768: {
            items: settings.cols_md ? settings.cols_md : 1,
            nav: settings.arrows ? settings.arrows : false,
          },
          992: {
            items: settings.cols_lg ? settings.cols_lg : 1,
            nav: settings.arrows ? settings.arrows : false,
          },
          1200: {
            items: settings.cols_xl ? settings.cols_xl : 1,
            nav: settings.arrows ? settings.arrows : false,
          },
        },
      });
    }
  }

  // Make sure you run this code under Elementor.
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/yprm_testimonials.default', PTTestimonials);
  });

})(jQuery);