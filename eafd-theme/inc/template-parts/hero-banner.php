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
$hero_slides       = eafd_get_option( 'hero_slides', array() );

if ( empty( $hero_slides ) && ! empty( $hero_banner_url ) ) {
	$hero_slides = array(
		array(
			'desktop_img' => $hero_banner_url,
			'mobile_img'  => $hero_banner_url,
			'title'       => '',
			'subtitle'    => $hero_location_tag,
			'btn_text'    => '',
			'btn_link'    => '#',
		)
	);
}
?>

<div class="eafd-hero-banner-container">
	<div class="eafd-hero-slider-wrapper">
		<?php if ( ! empty( $hero_slides ) ) : ?>
			<div class="eafd-hero-slider" id="eafdHeroSlider">
				<div class="eafd-slider-track">
					<?php foreach ( $hero_slides as $index => $slide ) : ?>
						<?php
						$desktop_img = ! empty( $slide['desktop_img'] ) ? $slide['desktop_img'] : $hero_banner_url;
						$mobile_img  = ! empty( $slide['mobile_img'] ) ? $slide['mobile_img'] : $desktop_img;
						$title       = ! empty( $slide['title'] ) ? $slide['title'] : '';
						$subtitle    = ! empty( $slide['subtitle'] ) ? $slide['subtitle'] : $hero_location_tag;
						$btn_text    = ! empty( $slide['btn_text'] ) ? $slide['btn_text'] : '';
						$btn_link    = ! empty( $slide['btn_link'] ) ? $slide['btn_link'] : '';
						?>
						<div class="eafd-slide <?php echo 0 === $index ? 'active' : ''; ?>" data-slide-index="<?php echo $index; ?>">
							<picture class="eafd-slide-picture">
								<?php if ( ! empty( $mobile_img ) ) : ?>
									<source media="(max-width: 768px)" srcset="<?php echo esc_url( $mobile_img ); ?>">
								<?php endif; ?>
								<img src="<?php echo esc_url( $desktop_img ); ?>" alt="<?php echo esc_attr( $title ? $title : 'بنر اصلی' ); ?>" class="eafd-hero-img" width="1200" height="450" loading="<?php echo 0 === $index ? 'eager' : 'lazy'; ?>" fetchpriority="<?php echo 0 === $index ? 'high' : 'low'; ?>" />
							</picture>

							<?php if ( $title || $subtitle || $btn_text ) : ?>
								<div class="eafd-slide-content">
									<?php if ( $subtitle ) : ?>
										<div class="eafd-hero-location-pill">
											<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
												<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
												<circle cx="12" cy="10" r="3"></circle>
											</svg>
											<span><?php echo esc_html( $subtitle ); ?></span>
										</div>
									<?php endif; ?>

									<?php if ( $title ) : ?>
										<h2 class="eafd-slide-title"><?php echo esc_html( $title ); ?></h2>
									<?php endif; ?>

									<?php if ( $btn_text && $btn_link ) : ?>
										<a href="<?php echo esc_url( $btn_link ); ?>" class="eafd-slide-btn"><?php echo esc_html( $btn_text ); ?></a>
									<?php endif; ?>
								</div>
							<?php elseif ( ! empty( $hero_location_tag ) ) : ?>
								<div class="eafd-hero-location-pill">
									<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
										<circle cx="12" cy="10" r="3"></circle>
									</svg>
									<span><?php echo esc_html( $hero_location_tag ); ?></span>
								</div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>

				<?php if ( count( $hero_slides ) > 1 ) : ?>
					<button class="eafd-slider-nav eafd-slider-prev" aria-label="اسلاید قبلی">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
					</button>
					<button class="eafd-slider-nav eafd-slider-next" aria-label="اسلاید بعدی">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
					</button>

					<div class="eafd-slider-dots">
						<?php foreach ( $hero_slides as $index => $slide ) : ?>
							<button class="eafd-slider-dot <?php echo 0 === $index ? 'active' : ''; ?>" data-slide-target="<?php echo $index; ?>" aria-label="انتقال به اسلاید <?php echo $index + 1; ?>"></button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php else : ?>
			<div class="eafd-hero-placeholder">
				<div class="eafd-hero-placeholder-inner">
					<h2>محصولات ارگانیک و طبیعی</h2>
					<p>کیفیت ممتاز، مستقیم از تولیدکننده</p>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
