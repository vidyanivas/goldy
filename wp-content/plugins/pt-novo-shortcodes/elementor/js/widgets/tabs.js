(function ($) {
  "use strict";

  var ptTabs = function ($scope, $) {
    var wrapper = $scope.find('.pt-tabs.tabs');
    
    if (!wrapper.length) {
      return false;
    }

    wrapper.find('.tabs-head').on('click', '.item:not(.active-tab)', function () {
      jQuery(this).addClass('active-tab').siblings().removeClass('active-tab').parents('.tabs').find('.tabs-body .item').eq(jQuery(this).index()).fadeIn(150).siblings().hide();
    });

    wrapper.each(function () {
      var item = jQuery(this).find('.tabs-body > .item'),
        tabs_head = jQuery(this).find('.tabs-head');
      item.each(function () {
        var name = jQuery(this).data('name');

        tabs_head.append('<div class="item">' + name + '</div>');
      });

      tabs_head.find('.item:first-of-type').addClass('active-tab');
      jQuery(this).find('.tabs-body > .item:first-of-type').css('display', 'block');
    });

  }

  // Make sure you run this code under Elementor.
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/yprm_tabs.default', ptTabs);
  });

})(jQuery);