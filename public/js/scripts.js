/**
 * scripts.js
 */

// global variables
var map;
var infowindow;
var overlays = [];

// San Francisco coordinates to center the map on
var sfCoords = {lat: 37.7726952, lng: -122.3206986};

$(document).ready(function() {

    drawMap();

    // display default route
    displayRoute(1);

    // display selected route
    $("#route_select").change(function() {
        var routeNumber = $("#route_select").val();
        displayRoute(routeNumber);
    });
});

/**
 * Draws a google map.
 */
function drawMap() {

    var mapOptions = {
      center: sfCoords,
      zoom: 11,
      styles: [
        {
          stylers: [
            { visibility: "simplified" },
            { saturation: -100 }
          ]
        },
        {
          featureType: "water",
          stylers: [
            { hue: "#005eff" },
            { saturation: 67 }
          ]
        }
      ]
    };
    map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
}

/**
 * Adds a Marker and corresponding InfoWindow.
 */
function addMarker(station) {

    // create new Marker at station location
    //var image = "img/beachflag.png";
    var marker = new google.maps.Marker({
      position: new google.maps.LatLng(station.latitude, station.longitude),
      map: map,
      title: station.name
      //icon: image
    });

    // push Marker in overlays array
    overlays.push(marker);

    // create new InfoWindow on click on marker
    google.maps.event.addListener(marker, "click", function() {

        // close opened InfoWindows
        if (infowindow) {
            infowindow.close();
        }

        // query estimate departure time via Ajax
        $.ajax({
          url: "departures.php",
          data: {
            station_abbr: station.abbr
          },
          success: function(departures) {

              // create new InfoWindow
              infowindow = new google.maps.InfoWindow({
                content: departures
              });

              infowindow.open(map, marker);
          }
        });
    });
}

/**
 * Adds a Polyline to the map.
 */
function addPolyline(route) {

    // build an array of route config coordinates
    var routeCoords = [];
    route.config.forEach(function(station) {
        routeCoords.push(new google.maps.LatLng(station.latitude, station.longitude));
    });

    // create new Polyline
    var polyline = new google.maps.Polyline({
      path: routeCoords,
      strokeColor: route.color,
      strokeWeight: 4
    });

    // push Polyline to overlays array
    overlays.push(polyline);

    polyline.setMap(map);
}

/**
 * Displays a BART route on the map.
 */
function displayRoute(routeNumber) {

    // clear overlays (http://apitricks.blogspot.ch/2010/02/clearoverlays-in-v3.html)
    while (overlays[0]) {
        overlays.pop().setMap(null);
    }

    // query route infos via Ajax
    $.ajax({
      url: "route.php",
      data: {
        route_number: routeNumber
      },
      success: function(route) {

          route.config.forEach(function(station) {
              addMarker(station);
          });

          addPolyline(route);
      }
    });
}
