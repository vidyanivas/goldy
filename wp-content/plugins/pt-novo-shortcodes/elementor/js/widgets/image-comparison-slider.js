; (function ($) {
    "use strict";

    function Image_Dragger(dragElement, resizeElement, container) {
        dragElement.on('mousedown touchstart move', function (e) {

            dragElement.addClass('draggable');
            resizeElement.addClass('resizable');

            var startX = (e.pageX) ? e.pageX : e.originalEvent.touches[0].pageX,
                dragWidth = dragElement.outerWidth(),
                posX = dragElement.offset().left + dragWidth - startX,
                containerOffset = container.offset().left,
                containerWidth = container.outerWidth(),
                minLeft = containerOffset,
                maxLeft = containerOffset + containerWidth - dragWidth;

            dragElement.parents().on("mousemove touchmove", function (e) {

                var moveX = (e.pageX) ? e.pageX : e.originalEvent.touches[0].pageX,
                    leftValue = moveX + posX - dragWidth;

                if (leftValue < minLeft) {
                    leftValue = minLeft;
                } else if (leftValue > maxLeft) {
                    leftValue = maxLeft;
                }

                var widthValue = (leftValue + dragWidth / 2 - containerOffset) * 100 / containerWidth + '%';

                jQuery('.draggable').css('left', widthValue).on('mouseup touchend touchcancel', function () {
                    jQuery(this).removeClass('draggable');
                    resizeElement.removeClass('resizable');
                });
                jQuery('.resizable').css('width', widthValue);
            }).on('mouseup touchend touchcancel', function () {
                dragElement.removeClass('draggable');
                resizeElement.removeClass('resizable');
            });
            e.preventDefault();
        });
    }

	var Image_Comparison = {
        Before_After: function ($scope, $) {
            var $image_compare_wrap  = $scope.find('.image-comparison-slider'),
                $settings            = $image_compare_wrap.data('image-comparison-settings');
            
            var width = $image_compare_wrap.width() + 'px';
            $image_compare_wrap.find('.resize .old').css('width', width);
            Image_Dragger($image_compare_wrap.find('.line'), $image_compare_wrap.find('.resize'), $image_compare_wrap);

            $(window).on("resize.image-comparison", function(e) {
                var width = $image_compare_wrap.width() + 'px';
                $image_compare_wrap.find('.resize .old').css('width', width);
            });

            $(window).trigger("resize.image-comparison");
        }
	}

	// Elementor Addons
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/yprm_image_comparison_slider.default', Image_Comparison.Before_After );
    });

})(jQuery);