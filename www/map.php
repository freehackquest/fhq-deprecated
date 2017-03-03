<?php
	include_once("config/config.php");
	
	$api_key = $config['google_map']['api_key'];

?><!DOCTYPE html>
<html>
  <head>
    <title>FreeHackQuest Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <script src="js/libs/jquery-3.1.0.min.js"></script>
    <script type="text/javascript" src="js/fhq.js?ver=1"></script>
    <script type="text/javascript" src="js/fhq.ws.js?ver=1"></script>
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script>

var map;
var markers = [];
var bInitMap = false;
function initMap() {
	var fhq_main_server = new google.maps.LatLng(50.7374, 7.09821);

	map = new google.maps.Map(document.getElementById('map'), {
		center: fhq_main_server,
		zoom: 3
	});

	fhq.ws.getmap().done(function(r){
		console.log(r);
		for(var i = 0; i < r.data.length; i++){
			var t = r.data[i];
			markers.push(new google.maps.Marker({
				position: new google.maps.LatLng(t.lat, t.lng),
				map: map,
				label: "" + t.count
			}));
		}

		markers.push(new google.maps.Marker({
			position: fhq_main_server,
			map: map,
			label: 'fhq'
		}));
		var markerCluster = new MarkerClusterer(map, markers,
			{imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
	});
}

    </script>
    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $api_key; ?>&callback=initMap"
        async defer></script>
  </body>
</html>
