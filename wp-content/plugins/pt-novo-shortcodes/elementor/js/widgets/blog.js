(function ($) {
  "use strict";

  var PTBlogs = function ($scope, $) {
    var blog_block = $scope.find('.blog-block');

    if (!blog_block.length) {
      return false;
    }

    var $container = $('.blog-items');
    $container.imagesLoaded(function () {
      $container.isotope({
        itemSelector: 'article'
      });

      $container.prev('.filter-button-group').on('click', 'button', function () {
        jQuery(this).addClass('active').siblings().removeClass('active');
        var filterValue = jQuery(this).attr('data-filter');
        if (jQuery(this).parents('.blog-block').find('.loadmore-button').length > 0) {
          jQuery(this).parents('.blog-block').find('.loadmore-button').trigger('click', [false]);
        } else {
          $container.isotope({
            filter: filterValue
          });
        }

        jQuery(window).trigger('resize').trigger('scroll');
      });

      setTimeout(() => {
        $container.isotope('layout')
      }, 1000)
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

    if (blog_block.find('.loadmore-button').length) {
      var $this = blog_block;
      var $button = blog_block.find('.loadmore-button'),
        $filter = blog_block.find('.filter-button-group'),
        $items = blog_block.find('.load-wrap'),
        type = blog_block.attr('data-data-type'),
        count = $button.attr('data-count'),
        time = 0,
        re = 'article',
        action = 'loadmore_elementor_blog';

      blog_block.append('<div class="load-items-area"></div>');

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
          filter_value = '*',
          hide_button = false;

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
              hide_button = true
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

        $button.fadeIn().addClass('loading');

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

                let i = 20
                setInterval(() => {
                  if(i >= 0) {
                    i--;
                    $items.isotope('layout');
                  }
                }, 100)

                $button.attr('data-array', array).removeClass('loading');
                if (array == '[]') {
                  $button.parent().slideUp();
                }

                if(hide_button) {
                  $button.fadeOut();
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
    elementorFrontend.hooks.addAction('frontend/element_ready/yprm_blog.default', PTBlogs);
  });

})(jQuery);