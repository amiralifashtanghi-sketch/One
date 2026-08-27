<?php
/**
 * Footer template for Kish Harmony Theme (Expanded Design)
 */
$footer_options = get_option( 'kish_harmony_footer_options', array() );
$footer_text    = ! empty( $footer_options['footer_text'] ) ? $footer_options['footer_text'] : 'کیش هارمونی؛ مرجع رسمی رزرو خدمات و تفریحات جزیره کیش.';
$vip_text       = ! empty( $footer_options['vip_text'] ) ? $footer_options['vip_text'] : 'پشتیبانی ۲۴ ساعته VIP';
$address        = ! empty( $footer_options['address'] ) ? $footer_options['address'] : 'جزیره کیش، برج صدف، واحد ۲۰۴';
$map_link       = ! empty( $footer_options['map_link'] ) ? $footer_options['map_link'] : '#';
$phones         = ! empty( $footer_options['phones'] ) ? (array) $footer_options['phones'] : array( '076-44440000' );
$socials        = ! empty( $footer_options['socials'] ) ? (array) $footer_options['socials'] : array();
$trust_badges   = ! empty( $footer_options['trust_badges'] ) ? (array) $footer_options['trust_badges'] : array();
?>

<footer class="kish-footer">
	<!-- Dynamic Animated Wave Background -->
	<div class="footer-wave-wrapper">
		<svg class="footer-waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
			<defs>
				<path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
			</defs>
			<g class="parallax">
				<use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7)" />
				<use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)" />
				<use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)" />
				<use xlink:href="#gentle-wave" x="48" y="7" fill="#0B63D8" />
			</g>
		</svg>
	</div>

	<div class="footer-content">
		<div class="container">
			<div class="footer-grid">
				<div class="footer-col footer-about">
					<h3>درباره کیش هارمونی</h3>
					<p><?php echo esc_html( $footer_text ); ?></p>
					<?php if ( ! empty( $vip_text ) ) : ?>
						<div class="vip-badge"><i class="fa-solid fa-crown"></i> <?php echo esc_html( $vip_text ); ?></div>
					<?php endif; ?>

					<!-- Social Media Widgets -->
					<div class="footer-social-icons">
						<?php if ( ! empty( $socials['instagram'] ) ) : ?>
							<a href="<?php echo esc_url( $socials['instagram'] ); ?>" aria-label="اینستاگرام" target="_blank"><i class="fa-brands fa-instagram"></i></a>
						<?php endif; ?>
						<?php if ( ! empty( $socials['telegram'] ) ) : ?>
							<a href="<?php echo esc_url( $socials['telegram'] ); ?>" aria-label="تلگرام" target="_blank"><i class="fa-brands fa-telegram"></i></a>
						<?php endif; ?>
						<?php if ( ! empty( $socials['whatsapp'] ) ) : ?>
							<a href="<?php echo esc_url( $socials['whatsapp'] ); ?>" aria-label="واتساپ" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
						<?php endif; ?>
					</div>
				</div>

				<div class="footer-col footer-links">
					<h3>دسترسی سریع</h3>
					<?php
					wp_nav_menu( array(
						'theme_location' => 'footer_menu',
						'container'      => false,
						'menu_class'     => 'footer-menu-list',
						'fallback_cb'    => false,
					) );
					?>
				</div>

				<div class="footer-col footer-contact">
					<h3>ارتباط با ما</h3>
					<?php foreach ( $phones as $phone ) : ?>
						<p><i class="fa-solid fa-phone"></i> تلفن: <a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a></p>
					<?php endforeach; ?>

					<?php if ( ! empty( $address ) ) : ?>
						<p><i class="fa-solid fa-location-dot"></i> <a href="<?php echo esc_url( $map_link ); ?>" target="_blank"><?php echo esc_html( $address ); ?></a></p>
					<?php endif; ?>
				</div>

				<div class="footer-col footer-badges">
					<h3>نمادهای اعتماد</h3>
					<div class="trust-badges-grid">
						<?php foreach ( $trust_badges as $badge ) : ?>
							<?php if ( ! empty( $badge['code'] ) ) : ?>
								<div class="badge-item"><?php echo $badge['code']; ?></div>
							<?php elseif ( ! empty( $badge['img_url'] ) ) : ?>
								<a href="<?php echo esc_url( $badge['link'] ?: '#' ); ?>" target="_blank" class="badge-item">
									<img src="<?php echo esc_url( $badge['img_url'] ); ?>" alt="نماد اعتماد">
								</a>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

			<div class="footer-bottom">
				<p>© <?php echo date( 'Y' ); ?> کلیه حقوق مادی و معنوی متعلق به کیش هارمونی می‌باشد.</p>
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
