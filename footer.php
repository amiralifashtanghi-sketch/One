<?php
/**
 * Footer template for Kish Harmony Theme
 */
$footer_options = get_option( 'kish_harmony_footer_options', array() );
$enamad_code    = ! empty( $footer_options['enamad_code'] ) ? $footer_options['enamad_code'] : '';
$footer_text    = ! empty( $footer_options['footer_text'] ) ? $footer_options['footer_text'] : 'کیش هارمونی؛ مرجع رسمی رزرو خدمات و تفریحات جزیره کیش.';
$phone_number   = ! empty( $footer_options['phone_number'] ) ? $footer_options['phone_number'] : '076-44440000';
$vip_text       = ! empty( $footer_options['vip_text'] ) ? $footer_options['vip_text'] : 'پشتیبانی ۲۴ ساعته VIP';
?>

<footer class="kish-footer">
	<!-- Dynamic Animated Wave Background -->
	<div class="footer-wave-wrapper">
		<svg class="footer-waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
			<defs>
				<path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
			</defs>
			<g class="parallax">
				<use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7" />
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
					<p><i class="fa-solid fa-phone"></i> تلفن پشتیبانی: <a href="tel:<?php echo esc_attr( $phone_number ); ?>"><?php echo esc_html( $phone_number ); ?></a></p>
					<p><i class="fa-solid fa-location-dot"></i> جزیره کیش، برج صدف، واحد ۲۰۴</p>
				</div>

				<div class="footer-col footer-badges">
					<h3>نمادهای اعتماد</h3>
					<div class="trust-badges">
						<?php echo $enamad_code; // Allowed raw output for iframe/script if set by admin ?>
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
