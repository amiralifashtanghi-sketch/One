<?php
/**
 * Advanced Multi-Banner Slider Template (6-Layer Glassmorphism & Autoplay)
 */
$options      = get_option( 'kish_harmony_banner_options', array() );
$banners      = ! empty( $options['banners'] ) && is_array( $options['banners'] ) ? $options['banners'] : array();
$auto_scroll  = isset( $options['auto_scroll'] ) ? $options['auto_scroll'] : '1';
$scroll_speed = ! empty( $options['scroll_speed'] ) ? intval( $options['scroll_speed'] ) * 1000 : 4000;

if ( empty( $banners ) ) {
	return;
}
?>

<div class="slider-container" id="sliderContainer" data-autoscroll="<?php echo esc_attr( $auto_scroll ); ?>" data-speed="<?php echo esc_attr( $scroll_speed ); ?>">
	<!-- Nav Prev/Next Buttons -->
	<?php if ( count( $banners ) > 1 ) : ?>
		<button class="nav-btn prev" id="sliderPrev" aria-label="قبلی">&#10095;</button>
		<button class="nav-btn next" id="sliderNext" aria-label="بعدی">&#10094;</button>
	<?php endif; ?>

	<div class="slider" id="bannerSliderTrack">
		<?php foreach ( $banners as $idx => $b ) :
			$title       = ! empty( $b['title'] ) ? $b['title'] : '';
			$subtitle    = ! empty( $b['subtitle'] ) ? $b['subtitle'] : '';
			$bg_image    = ! empty( $b['bg_image'] ) ? $b['bg_image'] : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1400&q=80';
			$shark_image = ! empty( $b['shark_image'] ) ? $b['shark_image'] : '';
			$map_image   = ! empty( $b['map_image'] ) ? $b['map_image'] : '';
			$btn_text    = ! empty( $b['btn_text'] ) ? $b['btn_text'] : '';
			$btn_link    = ! empty( $b['btn_link'] ) ? $b['btn_link'] : '#';
		?>
			<div class="slide" style="background-image: url('<?php echo esc_url( $bg_image ); ?>');">
				<?php if ( ! empty( $map_image ) ) : ?>
					<img src="<?php echo esc_url( $map_image ); ?>" class="banner-map-layer" alt="نقشه کیش">
				<?php endif; ?>

				<div class="slide-content">
					<?php if ( ! empty( $title ) ) : ?><h2 class="slide-title"><?php echo esc_html( $title ); ?></h2><?php endif; ?>
					<?php if ( ! empty( $subtitle ) ) : ?><p class="slide-desc"><?php echo esc_html( $subtitle ); ?></p><?php endif; ?>
					<?php if ( ! empty( $btn_text ) ) : ?>
						<a href="<?php echo esc_url( $btn_link ); ?>" class="slide-btn"><?php echo esc_html( $btn_text ); ?> <i class="fa-solid fa-arrow-left"></i></a>
					<?php endif; ?>
				</div>

				<?php if ( ! empty( $shark_image ) ) : ?>
					<div class="banner-character-side">
						<img src="<?php echo esc_url( $shark_image ); ?>" class="banner-shark-character" alt="کاراکتر کیش">
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>

	<?php if ( count( $banners ) > 1 ) : ?>
		<div class="dots-container" id="sliderDots">
			<?php foreach ( $banners as $idx => $b ) : ?>
				<div class="dot <?php echo $idx === 0 ? 'active' : ''; ?>" data-index="<?php echo $idx; ?>"></div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
