<?php
$customMap = new CustomMap();
$img_path = $customMap->url;
$type = get_field('type');

$marker_purple = $img_path . 'img/icon-marker-purple.png';
$marker_green = $img_path . 'img/icon-marker-green.png';
$marker_yellow = $img_path . 'img/icon-marker-yellow.png';

$marker_purple_medium = $img_path . 'img/icon-marker-purple-medium.png';
$marker_green_medium = $img_path . 'img/icon-marker-green-medium.png';
$marker_yellow_medium = $img_path . 'img/icon-marker-yellow-medium.png';
?>

<div class='custom-map-wrap'>
	<div id="custom-map"></div>
</div>

<!-- Connect GMAP API -->
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $settings->gmap_api_key; ?>&libraries=places&callback=initCustomMap" async defer></script>

<!-- Setup the MAP -->
<script>
	function initCustomMap() {
		jQuery(document).ready(function ($) {
			// Define the location (latitude and longitude)
			var location = { lat: <?php echo $settings->latitude; ?>, lng: <?php echo $settings->longitude; ?> }; // Example: San Francisco
			
			<?php if (isset($type) && $type != ''): ?>
				<?php if ($type == 'performance'): ?>
					var icon = '<?php echo $marker_green_medium; ?>'
				<?php elseif ($type == 'training'): ?>
					var icon = '<?php echo $marker_yellow_medium; ?>'
				<?php elseif ($type == 'reformer'): ?>
					var icon = '<?php echo $marker_purple_medium; ?>'
				<?php else: ?>
					var icon = '';
				<?php endif; ?>
			<?php endif; ?>
			
			var customMapStyle = <?php echo json_encode($settings->map_design); ?>;

			// Create the map instance
			var map = new google.maps.Map(document.getElementById('custom-map'), {
				zoom: 14,
				center: location,
				styles: customMapStyle
			});

			// Add a marker
			var marker = new google.maps.Marker({
				position: location,
				map: map,
				title: '<?php echo $settings->map_title; ?>', // Optional: Marker title
				icon: icon
			});

			// Add an info window
			var infoWindow = new google.maps.InfoWindow({
				content: '<?php echo $settings->map_info_window; ?>',
			});
			
			// Auto-open the info window when the map loads
			infoWindow.open(map, marker);

			// Open the info window when the marker is clicked
			marker.addListener('click', function () {
				infoWindow.open(map, marker);
			});
		});
	}
</script>