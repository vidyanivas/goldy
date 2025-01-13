(function ($) {
  "use strict";

  var BannerWithCategories = function ($scope, $) {
    var category_block = $scope.find('.category-block');

    if (!category_block.length) {
      return false;
    }

    var settings = category_block.data('portfolio-settings');


    

    if (settings.carousel) {
      let responsive = {
        0: {
          items: 1
        }
      };

      if(settings.cols_xs) {
        responsive[0] = {
          items: settings.cols_xs
        }
      }

      if(settings.cols_sm) {
        responsive[576] = {
          items: settings.cols_sm
        }
      }

      if(settings.cols_md) {
        responsive[768] = {
          items: settings.cols_md
        }
      }

      if(settings.cols_lg) {
        responsive[992] = {
          items: settings.cols_lg
        }
      }

      if(settings.cols_xl) {
        responsive[1200] = {
          items: settings.cols_xl
        }
      }

      if (category_block.find('.item').length > 1) {
        category_block.addClass('owl-carousel').owlCarousel({
          loop: true,
          items: 1,
          dots: false,
          nav: settings.arrows,
          autoplay: settings.autoplay,
          autoplayTimeout: settings.autoplay_speed ? settings.autoplay_speed : 5000,
          autoplayHoverPause: settings.pauseohover,
          smartSpeed: settings.speed ? settings.speed : 250,
          navClass: ['owl-prev basic-ui-icon-left-arrow', 'owl-next basic-ui-icon-right-arrow'],
          navText: false,
          responsive: responsive,
        });
      }
    } else {
      category_block.find('.item').addClass('col-12 col-sm-' + (12 / settings.cols_sm) + ' col-md-' + (12 / settings.cols_sm) + ' col-lg-' + (12 / settings.cols_lg) + ' col-xl-' + (12 / settings.cols_xl) + '');
    }
  }

  // Make sure you run this code under Elementor.
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/yprm_categories.default', BannerWithCategories);
  });

})(jQuery);