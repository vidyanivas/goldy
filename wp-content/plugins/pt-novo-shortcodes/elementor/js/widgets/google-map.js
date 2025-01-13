(function ($) {
  "use strict";

  var GoogleMapHandler = function ($scope, $) {
    var mapid = $scope.find('.google-map'),
      maptype = $(mapid).data("google-map-type"),
      zoom = $(mapid).data("google-map-zoom"),
      map_lat = $(mapid).data("google-map-lat"),
      map_lng = $(mapid).data("google-map-lng"),
      styles = ($(mapid).data("google-map-style") != '') ? $(mapid).data("google-map-style") : '',
      active_info,
      infowindow,
      map;

    function initMap() {
      var myLatLng = {
        lat: parseFloat(map_lat),
        lng: parseFloat(map_lng)
      };

      var map = new google.maps.Map(mapid[0], {
        center: myLatLng,
        zoom: zoom,
        mapTypeId: maptype,
        disableDefaultUI: true,
        scrollwheel: true,
        styles: styles,
      });

      var markersLocations = $(mapid).data('google-locations');

      var icon = '';

      if (markersLocations.pin_icon == 'custom') {
        icon = markersLocations.pin_custom_marker
      }

      var marker = new google.maps.Marker({
        map: map,
        position: new google.maps.LatLng(parseFloat(markersLocations.lat), parseFloat(markersLocations.lng)),
        icon: icon,
      });

    }
    initMap();
  };

  // Make sure you run this code under Elementor..
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/yprm_google_map.default', GoogleMapHandler);
  });

})(jQuery);