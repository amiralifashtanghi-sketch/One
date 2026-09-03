<?php
/**
 * Hero Banner Component
 *
 * @package EAFD_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hero_banner_url   = eafd_get_option( 'hero_banner_url', '' );
$hero_location_tag = eafd_get_option( 'hero_location_tag', 'سبزوار - توحید شهر - فرزاندگان ۵' );
?>

<div class="eafd-hero-banner-container">
	<div class="eafd-hero-card">
		<?php if ( $hero_banner_url ) : ?>
			<img src="<?php echo esc_url( $hero_banner_url ); ?>" alt="بنر اصلی" class="eafd-hero-img" width="1200" height="450" loading="eager" fetchpriority="high" />
		<?php else : ?>
			<div class="eafd-hero-placeholder">
				<div class="eafd-hero-placeholder-inner">
					<h2>محصولات ارگانیک و طبیعی</h2>
					<p>کیفیت ممتاز، مستقیم از تولیدکننده</p>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $hero_location_tag ) ) : ?>
			<div class="eafd-hero-location-pill">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
					<circle cx="12" cy="10" r="3"></circle>
				</svg>
				<span><?php echo esc_html( $hero_location_tag ); ?></span>
			</div>
		<?php endif; ?>
	</div>
</div>
