<?php
/**
 * KishPedia Banner Section Template (7-Plan Specification)
 */

$bg_img        = get_option( 'kishpedia_bg_img', '' );
$map_img       = get_option( 'kishpedia_map_img', '' );
$character_img = get_option( 'kishpedia_character_img', '' );

$title_p1      = get_option( 'kishpedia_title_p1', 'راهنمای سفر و برنامه‌ریزی برای' );
$title_p2      = get_option( 'kishpedia_title_p2', 'جزیره کیش' );
$title_p3      = get_option( 'kishpedia_title_p3', 'کامل' );
$desc          = get_option( 'kishpedia_desc', 'همه چیز برای یک سفر بی‌نظیر، آسان و خاطره‌انگیز به جزیره کیش' );

$btn_left_text  = get_option( 'kishpedia_btn_left_text', 'کیش پدیا' );
$btn_left_link  = get_option( 'kishpedia_btn_left_link', get_post_type_archive_link( 'post' ) ?: '#' );
$btn_right_text = get_option( 'kishpedia_btn_right_text', 'رزرو تفریحات جزیره' );
$btn_right_link = get_option( 'kishpedia_btn_right_link', '#' );

$inline_bg_style = ! empty( $bg_img ) ? 'style="background-image: url(' . esc_url( $bg_img ) . ');"' : '';
?>

<div class="kishpedia-section-outer">
	<div class="container">

		<div class="kishpedia-banner-container" <?php echo $inline_bg_style; ?>>
			<!-- Layer 2: Island Map Image -->
			<?php if ( ! empty( $map_img ) ) : ?>
				<img src="<?php echo esc_url( $map_img ); ?>" alt="نقشه جزیره کیش" class="island-map" />
			<?php else : ?>
				<div class="island-map-placeholder island-map"></div>
			<?php endif; ?>

			<!-- Layer 3: Left Blend Gradient Overlay -->
			<div class="left-blend-gradient"></div>

			<!-- Layer 4: Character Image -->
			<div class="character-wrapper">
				<?php if ( ! empty( $character_img ) ) : ?>
					<img src="<?php echo esc_url( $character_img ); ?>" alt="کاراکتر کوسه کیش" />
				<?php else : ?>
					<div class="character-placeholder"><i class="fa-solid fa-fish-fins"></i></div>
				<?php endif; ?>
			</div>

			<!-- Content Grid (Desktop: Left 40% spacer / Right 60% text content) -->
			<div class="banner-content-grid">
				<div class="left-col-spacer"></div>

				<div class="right-col-content">
					<!-- Title with Vertical Orange Accent Line -->
					<div class="h1-title-wrapper">
						<span class="orange-accent-line"></span>
						<h1 class="banner-title">
							<span class="text-blue-light"><?php echo esc_html( $title_p1 ); ?></span>
							<span class="text-blue-dark"><?php echo esc_html( $title_p2 ); ?></span>
							<span class="text-orange"><?php echo esc_html( $title_p3 ); ?></span>
						</h1>
					</div>

					<!-- Description -->
					<p class="banner-description"><?php echo esc_html( $desc ); ?></p>

					<!-- Feature Grid (6 Items) -->
					<div class="feature-grid">
						<div class="feature-item">
							<i class="fa-solid fa-camera"></i>
							<span>جاذبه‌ها و تفریحات</span>
						</div>
						<div class="feature-item">
							<i class="fa-solid fa-bed"></i>
							<span>هتل‌ها و اقامتگاه‌ها</span>
						</div>
						<div class="feature-item">
							<i class="fa-solid fa-ship"></i>
							<span>تور و بلیط</span>
						</div>
						<div class="feature-item">
							<i class="fa-solid fa-map-location-dot"></i>
							<span>نقشه و راهنمای مناطق</span>
						</div>
						<div class="feature-item">
							<i class="fa-solid fa-basket-shopping"></i>
							<span>خرید و مراکز تجاری</span>
						</div>
						<div class="feature-item">
							<i class="fa-solid fa-utensils"></i>
							<span>رستوران‌ها و کافه‌ها</span>
						</div>
					</div>

					<!-- Button Group (Slim Pill Shape Buttons) -->
					<div class="banner-buttons-group">
						<a href="<?php echo esc_url( $btn_right_link ); ?>" class="btn-pill btn-pill-right">
							<?php echo esc_html( $btn_right_text ); ?> <i class="fa-solid fa-chevron-left"></i>
						</a>
						<a href="<?php echo esc_url( $btn_left_link ); ?>" class="btn-pill btn-pill-left">
							<i class="fa-solid fa-map"></i> <?php echo esc_html( $btn_left_text ); ?>
						</a>
					</div>

					<!-- Footer Service Icons (3 Items) -->
					<div class="banner-footer-services">
						<div class="service-item">
							<i class="fa-solid fa-headset"></i>
							<span>پشتیبانی ۲۴ ساعته</span>
						</div>
						<div class="service-item">
							<i class="fa-solid fa-shield-halved"></i>
							<span>تضمین بهترین قیمت</span>
						</div>
						<div class="service-item">
							<i class="fa-solid fa-calendar-check"></i>
							<span>رزرو سریع و آسان</span>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
