<?php 
$data = custom_classes_tab_category($settings->category);
$theme_color= $settings->theme_color;
$theme_content_color = $settings->theme_content_color;
// view_array($data);
?>

<?php if ($data): ?>

<script>
	jQuery(function($) {
		const main = $('#custom-classes-tab-wrap-<?php echo $id; ?>');
		const tabMenu = main.find('.custom-classes-tab-ul li a');
		const tabContentWrap = main.find('.custom-classes-tab-content-wrap');
		const tabContent = main.find('.custom-classes-tab-content-wrap').find('.custom-classes-tab-content-row');
		
		tabMenu.on('click', function(e) {
			e.preventDefault();
			const btn = $(this);
			const id = btn.attr('data-id');
			const targetElement = tabContentWrap.find('.custom-classes-tab-content-row[data-id="'+id+'"]');
			
			// Update Button Classes
			tabMenu.removeClass('active');
			btn.addClass('active');
			
			// Update Content Classes
			tabContent.removeClass('active');
			targetElement.addClass('active');
		});
	});
</script>

<div class='custom-classes-tab-wrap <?php echo $theme_color; ?>' id='custom-classes-tab-wrap-<?php echo $id; ?>'>
	<ul class='custom-classes-tab-ul' id="custom-classes-tab-ul-<?php echo $id; ?>">
		<?php foreach($data as $d_key => $d): ?>
			<li><a class="<?php echo ($d_key == 0) ? 'active' : ''; ?>" href='#' data-id="<?php echo $d['id']; ?>"><?php echo (!empty($d['tab_menu_title'])) ? $d['tab_menu_title'] : $d['title']; ?></a></li>
		<?php endforeach; ?>
	</ul>
	
	<div class='custom-classes-tab-content-wrap <?php echo $theme_content_color; ?>'>
		<?php foreach($data as $e_key => $e): ?>
			<div class='custom-classes-tab-content-row <?php echo ($e_key == 0) ? 'active' : ''; ?>' data-id="<?php echo $e['id']; ?>">
				<div class='custom-classes-tab-content-left'>
					<div>
						<img src='<?php echo $e['image']; ?>' />
					</div>
				</div>
				<div class='custom-classes-tab-content-right'>
					<h3 class='custom-classes-tab-content-title'>
						<?php echo separateToneStrong($e['title']); ?>
					</h3>
					<div class='custom-classes-tab-content-text'>
						<?php echo wpautop($e['content']); ?>
					</div>
					<div class='custom-classes-tab-content-btn-wrap'>
						<a href='<?php echo get_permalink(347); ?>'>Find &amp; Book</a>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>

<?php endif; ?>