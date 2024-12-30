<?php
$customStoreLocator = new CustomStoreLocator();
$data = $customStoreLocator->get_data();
$img_path = $customStoreLocator->url;
$sample_img = $img_path . 'img/img-sample-map.jpg';
$icon_search = $img_path . 'img/icon-search.png';
$marker_purple = $img_path . 'img/icon-marker-purple.png';
$marker_green = $img_path . 'img/icon-marker-green.png';
$marker_yellow = $img_path . 'img/icon-marker-yellow.png';

$marker_purple_medium = $img_path . 'img/icon-marker-purple-medium.png';
$marker_green_medium = $img_path . 'img/icon-marker-green-medium.png';
$marker_yellow_medium = $img_path . 'img/icon-marker-yellow-medium.png';

$show_listings = $settings->show_listings;
?>

<div class='custom-store-locator-wrap <?php echo ($show_listings == 'no') ? 'full' : ''; ?>'>
	
	<?php if ($show_listings == 'yes'): ?>
	
	<div class='custom-store-locator-left'>
		
		<!-- 	Search	 -->
		<form class='custom-store-locator-search-wrap'>
			<div class='custom-store-locator-search-input'>
				<input type="text" placeholder="Postcode or Address..." />
			</div>
			<div class='custom-store-locator-search-btn'>
				<button><img src="<?php echo $icon_search; ?>" alt="search" /></button>
			</div>
		</form>
		
		<!--  Listings -->
		<?php if ($data): ?>
			<div class='custom-store-locator-lists'>
				<?php foreach($data as $index => $d): ?>
				
					<?php 
						$type = $d['type'];
					?>
					<div class='custom-store-locator-list' id="custom-store-locator-store-<?php echo $index; ?>" data-search-keyword="<?php echo $d['search_keyword']; ?>">
						<div class='custom-store-locator-list-header'>
							<div class='custom-store-locator-list-marker'>
								<?php if ($type == 'training'): ?>
									<img src="<?php echo $marker_yellow; ?>" />
								<?php elseif ($type == 'performance'): ?>
									<img src="<?php echo $marker_green; ?>" />
								<?php elseif ($type == 'reformer'): ?>
									<img src="<?php echo $marker_purple; ?>" />
								<?php endif; ?>
								
							</div>

							<div class='custom-store-locator-list-content'>
								<div class='custom-store-locator-list-title'>
									<?php echo $d['title']; ?>
								</div>

								<div class='custom-store-locator-list-type'>
									ToneLAB: <span><?php echo $type; ?></span>
								</div>

								<div class='custom-store-locator-list-address'>
									<?php echo $d['formatted_address']; ?>
								</div>
							</div>
						</div>

						<div class='custom-store-locator-list-btn-wrap'>
							<?php if ($type == 'training'): ?>
								<button href='#' class='button yellow'>
									Studio Info
								</button>
							<?php elseif ($type == 'performance'): ?>
								<button href='#' class='button green'>
									Studio Info
								</button>
							<?php elseif ($type == 'reformer'): ?>
								<button href='#' class='button'>
									Studio Info
								</button>
							<?php endif; ?>
							
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else: ?>
			<p style="text-align:center;">
				No store location found.
			</p>
		<?php endif; ?>
		
	</div>
	
	<?php endif; ?>
	
	<div class='custom-store-locator-right'>
		<?php /* <img src="<?php echo $sample_img; ?>" alt="sample map" /> */?> 
		<div id="custom-store-locator-map"></div>
	</div>
</div>

<!-- Connect GMAP API -->
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $settings->gmap_api_key; ?>&libraries=places&callback=initMap" async defer></script>

<!-- Setup the MAP -->
<script>
	function initMap() {
		jQuery(document).ready(function ($) {

			// Scroll Sidebar When Clicked
			function scrollSidebar(btn) {
				
				if (btn.length == 0) return;
				
				// Get the parent element
				const parent = $('.custom-store-locator-lists');

				// Get the offset of the button element from the top of the document
				const btnOffset = btn.offset().top;

				// Get the offset of the parent element from the top of the document
				const parentOffset = parent.offset().top;

				// Get the scroll position of the parent
				const parentScrollTop = parent.scrollTop();

				// Calculate the element's offset relative to the parent
				const relativeOffset = btnOffset - parentOffset + parentScrollTop;

				// Scroll the container to bring the button into view
				parent.animate({
					scrollTop: relativeOffset
				}, 1000); // Adjust the scroll duration as needed
			}

			// Marker IMG
			function markerImg(type) {
				if (type == 'training') {
					return '<?php echo $marker_yellow_medium; ?>'
				} else if (type == 'performance') {
					return '<?php echo $marker_green_medium; ?>'
				} else if (type == 'reformer') {
					return '<?php echo $marker_purple_medium; ?>'
				}
			}

			// Button Color
			function buttonColor(type) {
				if (type == 'training') {
					return 'yellow'
				} else if (type == 'performance') {
					return 'green'
				} else if (type == 'reformer') {
					return 'purple'
				}
			}

			// Info Window Content HTML
			function infoWindowContentHTML(data) {
				return `<div class="custom-store-locator-marker-info">
<div class="custom-store-locator-title">${data.title}</div>
<div class="custom-store-locator-type">ToneLab: <span>${data.type}</span></div>
<div class="custom-store-locator-address"><span>${data.formatted_address}</span></div>
<div class="custom-store-locator-btn-wrap">
<a href="${data.url}" class="${buttonColor(data.type)}">View Studio Info</a>
	</div>
	</div>`
			}

			// Function to hide/show stores based on search term
			function toggleStores(searchTerm) {
				$('.custom-store-locator-lists .custom-store-locator-list').each(function () {
					const storeKeyword = $(this).data('search-keyword').toLowerCase();

					if (storeKeyword.includes(searchTerm.toLowerCase())) {
						$(this).show(); // Show if keyword matches
					} else {
						$(this).hide(); // Hide if no match
					}
				});
			}

			const storesData = <?php echo json_encode($data, JSON_HEX_TAG); ?>;
			const customStyle = <?php echo json_encode($settings->map_design) ?>;

			let map;
			let markers = [];
			let currentInfoWindow = null;
			const locations = storesData; // Replace this with dynamic data from PHP

			const $mapElement = $('#custom-store-locator-map'); // Use jQuery to select the map element

			// Initialize map
			map = new google.maps.Map($mapElement[0], {
				center: { lat: 0, lng: 0 }, // Default center
				zoom: 10,
				styles: customStyle
			});

			// Add markers
			$.each(locations, function (index, location) {
				const locationLat = location.address.lat
				const locationLang = location.address.lng

				const marker = new google.maps.Marker({
					position: { lat: parseFloat(locationLat), lng: parseFloat(locationLang) },
					map: map,
					title: location.title,
					icon: {
						url: markerImg(location.type), // Path to your custom image
					}
				});

				// InfoWindow
				const infoWindow = new google.maps.InfoWindow({
					content: infoWindowContentHTML(location),
				});

				// Click marker to open InfoWindow
				google.maps.event.addListener(marker, 'click', function () {
					// Close the previously opened InfoWindow (if any)
					if (currentInfoWindow) {
						currentInfoWindow.close();
					}

					infoWindow.open(map, marker);

					map.panTo(marker.getPosition());

					// Scroll from the left
					$('.custom-store-locator-list').removeClass('active');
					const btn = $(`#custom-store-locator-store-${index}`);
					btn.addClass('active');
					scrollSidebar(btn);

					// Update the currentInfoWindow to track the newly opened one
					currentInfoWindow = infoWindow;
				});

				// Connect marker to listing
				$(`#custom-store-locator-store-${index}`).on('click', function () {
					const btn = $(this);

					scrollSidebar(btn);

					$('.custom-store-locator-list').removeClass('active');

					btn.addClass('active');

					map.panTo(marker.getPosition());

					if (currentInfoWindow) {
						currentInfoWindow.close(); // Close the currently open InfoWindow
					}

					infoWindow.open(map, marker);

					// Update the currentInfoWindow to track the newly opened one
					currentInfoWindow = infoWindow;
				});

				markers.push(marker);
			});

			// Center map based on markers
			const bounds = new google.maps.LatLngBounds();

			$.each(markers, function (index, marker) {
				bounds.extend(marker.getPosition());
			});

			map.fitBounds(bounds);

			$('.custom-store-locator-search-input input').on('input', function () {
				const input = $(this);
				const inputVal = input.val();
				toggleStores(inputVal)
			});

			// Search Form Submitted
			$('.custom-store-locator-search-wrap').submit(function(e) {
				e.preventDefault();
				const form = $(this);
				const inputVal = form.find('input').val();
				toggleStores(inputVal)
			});
		});
	}
</script>