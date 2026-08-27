<?php
/**
 * Banner Slider Section Template
 */
$options      = get_option( 'kish_harmony_banner_options', array() );
$banners      = ! empty( $options['banners'] ) ? $options['banners'] : array();
$auto_scroll  = isset( $options['auto_scroll'] ) ? $options['auto_scroll'] : '1';
$scroll_speed = ! empty( $options['scroll_speed'] ) ? intval( $options['scroll_speed'] ) * 1000 : 5000;

if ( empty( $banners ) ) {
	return;
}
?>

<div class="banner-container-wrapper" data-autoscroll="<?php echo esc_attr( $auto_scroll ); ?>" data-speed="<?php echo esc_attr( $scroll_speed ); ?>">
	<div class="banner-slider-track" id="bannerSliderTrack">
		<?php foreach ( $banners as $idx => $b ) :
			$title       = ! empty( $b['title'] ) ? $b['title'] : '';
			$subtitle    = ! empty( $b['subtitle'] ) ? $b['subtitle'] : '';
			$bg_image    = ! empty( $b['bg_image'] ) ? $b['bg_image'] : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1300&q=80';
			$shark_image = ! empty( $b['shark_image'] ) ? $b['shark_image'] : '';
			$map_image   = ! empty( $b['map_image'] ) ? $b['map_image'] : '';
			$btn_text    = ! empty( $b['btn_text'] ) ? $b['btn_text'] : '';
			$btn_link    = ! empty( $b['btn_link'] ) ? $b['btn_link'] : '#';
		?>
			<div class="slide <?php echo $idx === 0 ? 'active' : ''; ?>" style="background-image: url('<?php echo esc_url( $bg_image ); ?>');">
				<?php if ( ! empty( $map_image ) ) : ?>
					<img src="<?php echo esc_url( $map_image ); ?>" class="banner-map-layer" alt="نقشه کیش">
				<?php endif; ?>

				<div class="banner-gradient-left"></div>

				<div class="banner-content-grid">
					<div class="banner-text-side">
						<?php if ( ! empty( $title ) ) : ?><h1 class="banner-title"><?php echo esc_html( $title ); ?></h1><?php endif; ?>
						<?php if ( ! empty( $subtitle ) ) : ?><p class="banner-subtitle"><?php echo esc_html( $subtitle ); ?></p><?php endif; ?>
						<?php if ( ! empty( $btn_text ) ) : ?>
							<a href="<?php echo esc_url( $btn_link ); ?>" class="banner-btn"><?php echo esc_html( $btn_text ); ?> <i class="fa-solid fa-arrow-left"></i></a>
						<?php endif; ?>
					</div>

					<div class="banner-character-side">
						<?php if ( ! empty( $shark_image ) ) : ?>
							<img src="<?php echo esc_url( $shark_image ); ?>" class="banner-shark-character" alt="کاراکتر کیش هارمونی">
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<?php if ( count( $banners ) > 1 ) : ?>
		<div class="banner-slider-dots" id="bannerSliderDots">
			<?php foreach ( $banners as $idx => $b ) : ?>
				<span class="dot <?php echo $idx === 0 ? 'active' : ''; ?>" data-slide="<?php echo $idx; ?>"></span>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
