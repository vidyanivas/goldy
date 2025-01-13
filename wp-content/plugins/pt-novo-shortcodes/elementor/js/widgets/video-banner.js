(function ($) {
  "use strict";

  var pt_video_banner = function ($scope, $) {
    var video_banner = $scope.find('.banner-area.video-banner .banner-items');

    if (!video_banner.length) {
      return false;
    }

    yprm_init_banner();

    var updateLayout = function () {
      var windowHeight = jQuery(window).outerHeight();
      var headerHeight = (jQuery('.site-header').outerHeight() || 0) +
                         (jQuery('.header + .navigation').outerHeight() || 0) +
                         (jQuery('.ypromo-site-bar').outerHeight() || 0);
      var footerHeight = (jQuery('.site-footer').outerHeight() || 0) +
                         (jQuery('.footer-social-button').outerHeight() || 0);
      var adminBarHeight = jQuery('#wpadminbar').outerHeight() || 0;

      // Update header space
      jQuery('.header-space').css('height', headerHeight);

      // Update main row min-height
      jQuery('main.main-row').css('min-height', windowHeight - footerHeight - adminBarHeight - headerHeight);

      // Update protected post form height
      jQuery('.protected-post-form .cell').css('height', windowHeight - footerHeight - adminBarHeight - headerHeight);

      // Update banners
      jQuery('.banner:not(.fixed-height)').each(function () {
        var coef = 0;
        var $banner = jQuery(this);

        if ($banner.parents('.banner-area').hasClass('external-indent') && !$banner.parents('.banner-area').hasClass('with-carousel-nav')) {
          coef = 70;
        }

        var bannerHeight = windowHeight - adminBarHeight - (jQuery('.header-space:not(.hide)').outerHeight() || 0) - coef;
        $banner.css('height', bannerHeight);
        $banner.find('.cell, .banner-categories .item, .banner-about .image, .banner-about .text, .banner-right-buttons .cell')
          .css('height', bannerHeight);
        $banner.parent().find('.banner-about .cell').css('height', bannerHeight - 20);
      });

      // Fixed-height banners
      jQuery('.banner.fixed-height').each(function () {
        var $banner = jQuery(this);
        var bannerHeight = $banner.height();
        $banner.find('.cell, .banner-categories .item, .banner-about .image, .banner-about .text, .banner-right-buttons .cell')
          .css('height', bannerHeight);
        $banner.parent().find('.banner-about .cell').css('height', bannerHeight - 20);
      });

      // Full-screen navigation
      jQuery('.full-screen-nav .cell').css('height', windowHeight - adminBarHeight - 20);

      // Side-header adjustments
      jQuery('.side-header .cell').css('height', jQuery('.side-header .wrap').height());
    };

    // Attach updateLayout to load and resize events
    jQuery(window).on("load resize", updateLayout);
  };

  // Make sure you run this code under Elementor
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/yprm_video_banner.default', pt_video_banner);
  });

})(jQuery);
