class GalleryHandler extends elementorModules.frontend.handlers.Base {
  onInit() {
    var portfolio_block = this.$element.find('.gallery-block');

    if (!portfolio_block.length) {
      return false;
    }

    var settings = portfolio_block.data('portfolio-settings');
    var $gallery = this.$element.find('.gallery-type-masonry');

    if ($gallery.length && settings.gallery_layout == 'masonry') {
      // init isotope
      if (!this.isEdit) {

        if ( typeof $gallery.data('isotope') !== 'undefined' ) {
            $gallery.isotope('destroy');
        }

        $gallery.imagesLoaded(() => {
          let isotope = $gallery.isotope({
            itemSelector: '.portfolio-item',
            masonry: {
              columnWidth: '.grid-sizer'
            },
            percentPosition: true
          });

          jQuery(window).trigger('resize').trigger('scroll');
        })
      }
    } else if (settings.gallery_layout == 'slider') {

      let autoplay = false,
        breakpoints = {}

      if (settings.autoplay) {
        autoplay = {
          delay: settings.autoplay_speed
        }
      }

      if (settings.cols_xs) {
        breakpoints[200] = {
          slidesPerView: settings.cols_xs
        }
      }
      if (settings.cols_sm) {
        breakpoints[576] = {
          slidesPerView: settings.cols_sm
        }
      }
      if (settings.cols_md) {
        breakpoints[768] = {
          slidesPerView: settings.cols_md
        }
      }
      if (settings.cols_lg) {
        breakpoints[992] = {
          slidesPerView: settings.cols_lg
        }
      }
      if (settings.cols_xl) {
        breakpoints[1200] = {
          slidesPerView: settings.cols_xl
        }
      }

      if (settings.slider_thumbs == 'yes') {
        var galleryThumbs = new Swiper(this.$element.find('.thumbs').get(0), {
          breakpointsInverse: true,
          spaceBetween: 5,
          slideToClickedSlide: true,
          watchOverflow: true,
          freeMode: true,
          loopedSlides: 5,
          slidesPerView: 4,
          watchSlidesProgress: true,
          breakpoints: {
            0: {
              slidesPerView: 3,
            },
            480: {
              slidesPerView: 4,
            },
            640: {
              slidesPerView: 5,
            },
            768: {
              slidesPerView: 7,
            },
            998: {
              slidesPerView: 8,
            },
            1200: {
              slidesPerView: 9,
            },
          }
        });

        var galleryTop = new Swiper(this.$element.find('.slider').get(0), {
          watchSlidesVisibility: true,
          loopAdditionalSlides: 2,
          speed: 800,
          loop: settings.loop ? true : false,
          autoplay: autoplay,
          navigation: {
            nextEl: this.$element.find('.next').get(0),
            prevEl: this.$element.find('.prev').get(0),
          },
          breakpoints: breakpoints,
          thumbs: {
            swiper: galleryThumbs
          }
        });
      } else {
        new Swiper(this.$element.find('.slider.swiper').get(0), {
          loop: settings.loop ? true : false,
          autoplay: autoplay,
          watchSlidesVisibility: true,
          loopAdditionalSlides: 2,
          navigation: {
            nextEl: this.$element.find('.next').get(0),
            prevEl: this.$element.find('.prev').get(0),
          },
          breakpointsInverse: true,
          breakpoints: breakpoints
        });
      }
    }
  }
}

jQuery(window).on('elementor/frontend/init', () => {
  const addHandler = ($element) => {
    elementorFrontend.elementsHandler.addHandler(GalleryHandler, {
      $element,
    });
  };

  elementorFrontend.hooks.addAction('frontend/element_ready/yprm_gallery.default', addHandler);
});