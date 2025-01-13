(function ($) {
  "use strict";

  var ptExternalGallery = function ($scope, $) {
    var portfolio_block = $scope.find('.portfolio-block');

    if (!portfolio_block.length) {
      return false;
    }

    portfolio_block.each(function () {
      var head_slider = jQuery(this),
        settings = head_slider.data('portfolio-settings');

      if (settings.type == 'masonry' || settings.type == 'scattered') {

        portfolio_block.find('.portfolio-items').each(function () {
          var wrap = jQuery(this);
          wrap.imagesLoaded(function () {
            var $grid = wrap.isotope({
              itemSelector: 'article',
              masonry: {
                //horizontalOrder: true
              }
            });

            wrap.prev('.filter-button-group').on('click', 'button', function () {
              jQuery(this).addClass('active').siblings().removeClass('active');
              var filterValue = jQuery(this).attr('data-filter');
              if (jQuery(this).parents('.portfolio-block').find('.loadmore-button').length > 0) {
                jQuery(this).parents('.portfolio-block').find('.loadmore-button').trigger('click', [false]);
              } else {
                $grid.isotope({
                  filter: filterValue
                });
              }

              jQuery(window).trigger('resize').trigger('scroll');
            });
          });
        });

      } else if (settings.type == 'flow') {
        head_slider.find('.portfolio-type-flow').flipster({
          style: 'carousel',
          loop: true,
          start: 2,
          spacing: -0.5,
          nav: false,
          buttons: true,
        });
      } else if (settings.type == 'horizontal') {

        if (head_slider.find('.item').length > 1) {
          head_slider.find('.portfolio-type-horizontal').imagesLoaded(function () {
            head_slider.find('.portfolio-type-horizontal').addClass('owl-carousel').owlCarousel({
              loop: true,
              items: 1,
              center: true,
              autoWidth: true,
              nav: settings.arrows ? settings.arrows : false,
              dots: false,
              autoplay: settings.autoplay ? settings.autoplay : false,
              autoplayTimeout: settings.autoplay_speed ? settings.autoplay_speed : false,
              autoplayHoverPause: true,
              smartSpeed: settings.speed ? settings.speed : false,
              navClass: ['owl-prev basic-ui-icon-left-arrow', 'owl-next basic-ui-icon-right-arrow'],
              navText: false,
              margin: 30,
              responsive: {
                0: {
                  nav: false,
                },
                768: {
                  nav: settings.arrows ? settings.arrows : false,
                },
              },
            });

            if (settings.mousewheel) {
              head_slider.find('.portfolio-type-horizontal').on('mousewheel', '.owl-stage', function (e) {
                e.preventDefault();
                var delta = e.originalEvent.deltaY;

                if (delta > 0) {
                  head_slider.find('.portfolio-type-horizontal').trigger('next.owl');
                } else {
                  head_slider.find('.portfolio-type-horizontal').trigger('prev.owl');
                }
              });
            }
          });
        }
      } else if (settings.type == 'carousel') {
        if (head_slider.find('.portfolio-type-carousel').find('.item').length > 1) {
          head_slider.find('.portfolio-type-carousel').imagesLoaded(function () {
            head_slider.find('.portfolio-type-carousel').addClass('owl-carousel').owlCarousel({
              loop: true,
              items: 1,
              center: true,
              autoWidth: true,
              dots: settings.arrows ? settings.arrows : false,
              nav: false,
              autoplay: settings.autoplay ? settings.autoplay : false,
              autoplayTimeout: settings.autoplay_speed ? settings.autoplay_speed : false,
              autoplayHoverPause: true,
              smartSpeed: settings.speed ? settings.speed : false,
              navClass: ['owl-prev basic-ui-icon-left-arrow', 'owl-next basic-ui-icon-right-arrow'],
              navText: false,
              margin: 30,
              responsive: {
                0: {
                  nav: false,
                },
                768: {
                  nav: settings.arrows ? settings.arrows : false,
                },
              },
            });
          });
        }
      } else if (settings.type == 'carousel-type2') {

        if (settings.mousewheel) {
          new Swiper(head_slider.find('.swiper').get(0), { 
            slidesPerView: 'auto',
            spaceBetween: 30,
            mousewheel: {},
          });
        } else {
          new Swiper(head_slider.find('.swiper').get(0), { 
            slidesPerView: 'auto',
            spaceBetween: 30,
          });
        }
        
        setTimeout(function () {
          new Swiper(head_slider.find('.swiper').get(0), { 
            slidesPerView: 'auto',
            spaceBetween: 30,
            mousewheel: {},
          });
        
          console.log('loaded');
        }, 5000);
        
      }
    });

    function rebuild_array(src, filt) {
      var result = [];

      for (let index = 0; index < src.length; index++) {
        let id = src[index].id,
          flag = false;
        for (let index2 = 0; index2 < filt.length; index2++) {
          let id2 = filt[index2].id;
          if (id == id2) {
            flag = true;
            break;
          }
        }

        if (!flag) {
          result.push(src[index]);
        }
      }

      return JSON.stringify(result);
    }

    function get_from_category(array, slug, count, return_type) {
      var result = [],
        i = 0;

      for (let index = 0; index < array.length; index++) {
        let flag = false;

        if (typeof array[index].cat === 'undefined') continue;
        for (let index2 = 0; index2 < array[index].cat.length; index2++) {
          if (array[index].cat[index2] == slug) {
            flag = true;
            break;
          }
        }

        if (flag) {
          i++;
          result.push(array[index]);
        }

        if (i == count && !return_type) {
          break;
        }
      }

      if (result == []) {
        return false;
      }

      return result;
    }

    var head_slider = $('#' + portfolio_block.attr('id'));
    if (head_slider.find('.elementor-loadmore-button').length) {
      var $this = head_slider;
      var $button = head_slider.find('.elementor-loadmore-button'),
        $filter = head_slider.find('.filter-button-group'),
        $items = head_slider.find('.load-wrap'),
        type = head_slider.attr('data-data-type'),
        count = $button.attr('data-count'),
        time = 0,
        re = 'article',
        action = 'loadmore_elementor_portfolio';

      if(!head_slider.find('.load-items-area').length) {
        head_slider.append('<div class="load-items-area"></div>');
      }

      if ($button.hasClass('load_more_on_scroll')) {
        jQuery(window).on('scroll', function () {
          $button.parent().prev().imagesLoaded(function () {
            var new_time = Date.now();

            if ((time + 1000) < new_time && !$button.hasClass('hide')) {
              var top = $button.offset().top - 800,
                w_top = jQuery(window).scrollTop() + jQuery(window).height();

              if (w_top > jQuery(window).height() + 150 && top < w_top) {
                $button.trigger('click');
              }

              time = new_time;
            }
          });
        });
      }

      $items.css('min-height', $items.find(re).height());

      $button.on('click', function (event, loading) {
        event.preventDefault();

        if (jQuery(this).hasClass('loading')) return false;

        if (typeof loading === 'undefined') {
          loading = true
        }

        var array = JSON.parse($button.attr('data-array')),
          atts = JSON.parse($button.attr('data-atts')),
          load_items = array.slice(0, count),
          filter_value = '*';

        if ($filter.length > 0) {
          var filter_value = $filter.find('.active').attr('data-filter'),
            slug = filter_value.replace('.category-', ''),
            current_count = $items.find(filter_value).length;

          if ($this.hasClass('product-block')) {
            slug = filter_value.replace('.product_cat-', '')
          }

          if (filter_value != '*') {
            var cat_full_length = get_from_category(array, slug, count, true).length,
              cat_length = get_from_category(array, slug, count, false).length;

            if (current_count < count && cat_full_length != 0) {
              load_items = get_from_category(array, slug, count - current_count, false);
              loading = true;
            } else if (loading) {
              load_items = get_from_category(array, slug, count, false);
            }

            if ((loading && cat_full_length - load_items.length <= 0) || (!loading && cat_full_length == 0)) {
              $button.fadeOut();
            } else {
              $button.fadeIn();
            }
          } else {
            $button.fadeIn();
          }

          $items.isotope({
            filter: filter_value
          });
        }

        if (array.length == 0) return false;
        if (!loading) {
          return false;
        }

        $button.addClass('loading');

        jQuery.ajax({
          url: yprm_ajax.url,
          type: "POST",
          data: {
            action: action,
            array: load_items,
            atts: atts,
            type: type,
            start_index: $this.find(re).length
          },
          success: function (data) {
            var temp_block = $this.find('.load-items-area').append(data);
            array = rebuild_array(array, load_items);

            temp_block.imagesLoaded(function () {
              var items = temp_block.find(re);
              $items.append(items).isotope('appended', items).isotope({
                filter: filter_value
              }).queue(function (next) {
                lazyLoad();
                next();

                $button.attr('data-array', array).removeClass('loading');
                if (array == '[]') {
                  $button.parent().slideUp();
                }
              });
            });
          },
          error: function (errorThrown) {
            console.log(errorThrown);
          }
        });
      });
    }
  }

  // Make sure you run this code under Elementor.
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/yprm_gallery_external_link.default', ptExternalGallery);
  });

})(jQuery);