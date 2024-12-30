<?php
// Get the sliders settings
$args = array(
    'post_type' => 'testimonials_rec', // Replace 'your_custom_post_type' with the actual slug of your custom post type
    'posts_per_page' => -1, // Number of posts to display per page
);
$query = new WP_Query($args);
?>
<?php if ($query->have_posts()): ?>
	<script type="text/javascript">
		jQuery(function($) {
			$('#custom-testimonial-slider-<?php echo $id; ?>').slick({
				slidesToShow: 1,
		  		slidesToScroll: 1,
		  		speed: 1000,
		  		dots: true,
		  		adaptiveHeight: true,
		  		arrows: false,
				autoplay: true,
		  		responsive: [
			    {
			      breakpoint: 801,
			      settings: {
			        slidesToShow: 1,
			        slidesToScroll:1,
			      }
			    },
			  ]
			});
		});
	</script>
	<div class="custom-testimonial-slider-row">
		<div class="normal-dots-slider white-dots-slider custom-testimonial-slider-wrap" id="custom-testimonial-slider-<?php echo $id; ?>">
			<?php while ($query->have_posts()): $query->the_post(); ?>
				<?php 
					$title = get_the_title();
					$img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
					$location = get_field('location');
					$content = wpautop(get_the_content());
				?>
				<div class="custom-testimonial-slider">
					<div class="custom-testi-slide-inner">
						
						<div class="custom-testi-cont">
							
							<?php /*<div class='custom-testi-star'>
								<img src="<?php echo site_url(); ?>/wp-content/uploads/2024/06/img-star-1.png" alt="5 Star" />
							</div>*/?>
							
							<div class='custom-testi-cont-main'>
								<?php echo $content; ?>
							</div>

							<div class='custom-testi-by'>
								<?php echo $title; ?>
							</div>
							
							<div class='custom-testi-loc'>
								<?php echo $location; ?>
							</div>
						</div><!-- end class pbg-testi-cont -->
						
						<?php /*
						<div class="custom-testi-info">
							<img src='<?php echo $img[0]; ?>' alt='<?php echo $title; ?>' />
						</div><!-- end class custom-testi-info --> */ ?>
					</div><!-- end class custom-testi-slide-inner -->
				</div><!-- end class custom-testimonial-slider -->
			<?php endwhile; wp_reset_postdata(); ?>
		</div><!-- end class custom-testimonial-slider-wrap -->
	</div><!-- end class custom-testimonial-slider-row -->
<?php endif; ?>