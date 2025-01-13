(function ($) {
  "use strict";

  var MusicAlbumItems = function ($scope, $) {
    var $wrapper = $scope.find('.jp_container_album'),
      $js_items = $wrapper.data('player-items'),
      album_area = $scope.find('.jp-audio.album-area'),
      $container = $wrapper.attr('id');

    if (!album_area.length) {
      return false;
    }

    let trtrt = new jPlayerPlaylist({
      jPlayer: '#jquery_jplayer_' + $container,
      cssSelectorAncestor: '#jp_container_' + $container,
      cssDuration: '#jp_duration_' + $container
    }, $js_items, {
      supplied: 'mp3',
      wmode: 'window',
      useStateClassSkin: true,
      autoBlur: false,
      smoothPlayBar: true,
      keyEnabled: true,
      ready: function (event) {
        jQuery('#jp_container_' + $container).find('.track-name').html(jQuery('#jp_container_' + $container).find('a.jp-playlist-current').html());
      },
      play: function (event) {
        jQuery('#jp_container_' + $container).find('.track-name').html(jQuery('#jp_container_' + $container).find('a.jp-playlist-current').html());
      },
    });

    jQuery('.jp-playlist').each(function () {
      jQuery(this).scrollbar();
    });
  }

  // Make sure you run this code under Elementor.
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/yprm_music_album_item.default', MusicAlbumItems);
  });

})(jQuery);