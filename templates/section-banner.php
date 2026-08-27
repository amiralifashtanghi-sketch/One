<?php
/**
 * Banner Section Template
 */
$options = get_option( 'kish_harmony_banner_options', array() );
$title       = ! empty( $options['title'] ) ? $options['title'] : 'سفر به جزیره زیبای کیش با کیش هارمونی';
$subtitle    = ! empty( $options['subtitle'] ) ? $options['subtitle'] : 'رزرو آنلاین بهترین تورها، تفریحات دریایی و اجاره خودرو با پشتیبانی اختصاصی';
$bg_image    = ! empty( $options['bg_image'] ) ? $options['bg_image'] : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1300&q=80';
$shark_image = ! empty( $options['shark_image'] ) ? $options['shark_image'] : '';
$map_image   = ! empty( $options['map_image'] ) ? $options['map_image'] : '';
$btn_text    = ! empty( $options['btn_text'] ) ? $options['btn_text'] : 'مشاهده پیشنهادهای ویژه';
$btn_link    = ! empty( $options['btn_link'] ) ? $options['btn_link'] : '#special-offers';
?>

<div class="banner-container-wrapper">
	<div class="kh-banner" style="background-image: url('<?php echo esc_url( $bg_image ); ?>');">
		<!-- Map Layer -->
		<?php if ( ! empty( $map_image ) ) : ?>
			<img src="<?php echo esc_url( $map_image ); ?>" class="banner-map-layer" alt="نقشه کیش">
		<?php endif; ?>

		<!-- Gradient Overlay -->
		<div class="banner-gradient-left"></div>

		<!-- Main Banner Content -->
		<div class="banner-content-grid">
			<div class="banner-text-side">
				<h1 class="banner-title"><?php echo esc_html( $title ); ?></h1>
				<p class="banner-subtitle"><?php echo esc_html( $subtitle ); ?></p>
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
</div>
