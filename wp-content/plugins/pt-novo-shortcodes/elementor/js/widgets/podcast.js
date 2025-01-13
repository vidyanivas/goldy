(function($) {
    "use strict";

    var MusicAlbumItems = function( $scope, $ ) {
        var $wrapper = $scope.find('.jp_container_podcast'),
            $title = $wrapper.data('title'),
            $track_url = $wrapper.data('track-url'),
            album_area =  $scope.find('.jp-audio.album-area'),
            $container = $wrapper.attr('id');

        if ( ! album_area.length ) {
            return false;
        }

        jQuery('#jquery_jplayer_'+$container).jPlayer({
            jPlayer: '#jquery_jplayer_'+$container,
            cssSelectorAncestor: '#jp_container_'+ $container,
            ready: function (event) {
                jQuery(this).jPlayer('setMedia', {
                    title: $title,
                    mp3: $track_url,
                });
            },
            supplied: 'mp3',
            wmode: 'window',
            useStateClassSkin: true,
            autoBlur: false,
            smoothPlayBar: true,
            keyEnabled: true,
            remainingDuration: false,
            toggleDuration: true,
        });
    }

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/yprm_podcast.default', MusicAlbumItems );
    });

})(jQuery);