<div class="item-map">
	<style>
		#map-canvas {
			width: {{ Config::get('laravel-events::map.width') }}px;
			height: {{ Config::get('laravel-events::map.height') }}px;
		}
	</style>
	<div id="map-canvas"></div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script>
	function initialize() {
		var mapLatlng = new google.maps.LatLng( {{ $event->getMapLatitude() }}, {{ $event->getMapLongitude() }});
		var mapOptions = {
			zoom: {{ $event->getMapZoom() }},
			center: mapLatlng
		}
		var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
		var markerLatlng = new google.maps.LatLng({{ $event->getMarkerLatitude() }}, {{ $event->getMarkerLongitude() }});
		var marker = new google.maps.Marker({
			position: markerLatlng,
			map: map,
			title: '{{ addslashes($event->marker_title) }}'
		});
	}
	google.maps.event.addDomListener(window, 'load', initialize);
</script>