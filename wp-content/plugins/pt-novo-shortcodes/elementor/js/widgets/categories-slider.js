(function ($) {
  "use strict";

  var CategoriesSlider = function ($scope, $) {
    var category_block = $scope.find('.category-slider-area');

    category_block.each(function () {
      var el_area = jQuery(this),
        slider_settings = category_block.data('portfolio-settings'),
        items = el_area.find('.item'),
        images_area = el_area.find('.category-slider-images'),
        flag = true;

      items.each(function () {
        jQuery(this).attr('data-eq', jQuery(this).index());
        images_area.append('<div class="img-item" style="background-image: url(' + jQuery(this).attr('data-image') + ')"><div class="num">' + leadZero(jQuery(this).index() + 1) + '</div></div>');
      });
  
      el_area.find('.category-slider').on('initialized.owl.carousel translated.owl.carousel', function (e) {
        var eq = jQuery(this).find('.center .item').attr('data-eq');
        images_area.find('.img-item').eq(eq).addClass('active').siblings().removeClass('active');
      });
    
      el_area.find('.category-slider').owlCarousel({
        loop: slider_settings.loop ?? false,
        items: 1,
        center: true,
        autoWidth: true,
        nav: slider_settings.carousel_nav ?? false,
        dots: false,
        autoplay: slider_settings.autoplay ?? false, // Autoplay feature enabled
        autoplayHoverPause: true,
        navText: false,
        mouseDrag: slider_settings.mousewheel ?? false,
        slideBy: 1,
        smartSpeed: slider_settings.speed ?? 250, // Set autoplay timeout to 5 seconds
        autoplaySpeed: slider_settings.autoplay_speed ?? false, // Set autoplay speed to 0.8 seconds
        navClass: ['owl-prev basic-ui-icon-left-arrow', 'owl-next basic-ui-icon-right-arrow'],
        navText: false,
      });
  
      el_area.on('mousewheel wheel', function (e) {
        if (!flag) return false;
        flag = false;
  
        var d = e.originalEvent.deltaY;
        if (e.originalEvent.deltaY) {
          d = e.originalEvent.deltaY;
        } else {
          d = e.deltaY;
        }
  
        if (d > 0) {
          el_area.find('.category-slider').trigger('next.owl');
        } else {
          el_area.find('.category-slider').trigger('prev.owl');
        }
  
        setTimeout(function () {
          flag = true
        }, 600)
        e.preventDefault();
      });
    });
  }

  // Make sure you run this code under Elementor.
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/yprm_categories_slider.default', CategoriesSlider);
  });

})(jQuery);