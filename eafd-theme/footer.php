<?php
/**
 * Footer Template
 *
 * @package EAFD_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$brand_title      = eafd_get_option( 'brand_title', 'محصولات ارگانیک سجاد برزویی' );
$footer_about     = eafd_get_option( 'footer_about', 'فروشگاه محصولات ارگانیک سجاد برزویی آماده ثبت سفارش آنلاین شماست.' );
$footer_phone     = eafd_get_option( 'footer_phone', '۰۵۱ND۴۴۱۴۳۳۵' );
$footer_address   = eafd_get_option( 'footer_address', 'سبزوار - توحید شهر - فرزاندگان ۵' );
$footer_enamad    = eafd_get_option( 'footer_enamad', '' );
$footer_copyright = eafd_get_option( 'footer_copyright', 'تمامی حقوق و مسئولیت این سایت متعلق به محصولات ارگانیک سجاد برزویی می باشد.' );
$cart_count       = class_exists( 'WooCommerce' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
?>

	</main><!-- #main -->

	<footer id="colophon" class="site-footer">
		<div class="eafd-container">
			<div class="eafd-footer-card">
				<div class="eafd-footer-grid">

					<!-- Card 1: About -->
					<div class="eafd-footer-widget-card">
						<h3 class="eafd-footer-title">
							<span class="eafd-title-dot"></span>
							درباره <?php echo esc_html( $brand_title ); ?>
						</h3>
						<p class="eafd-footer-text"><?php echo esc_html( $footer_about ); ?></p>
					</div>

					<!-- Card 2: Quick Links -->
					<div class="eafd-footer-widget-card">
						<h3 class="eafd-footer-title">
							<span class="eafd-title-dot"></span>
							دسترسی سریع
						</h3>
						<?php
						if ( has_nav_menu( 'footer' ) ) {
							wp_nav_menu( array(
								'theme_location' => 'footer',
								'menu_class'     => 'eafd-footer-nav-list',
								'container'      => false,
							) );
						} else {
							?>
							<ul class="eafd-footer-nav-list">
								<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">صفحه اصلی</a></li>
								<li><a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : '#' ); ?>">حساب کاربری</a></li>
								<li><a href="#">قوانین سایت</a></li>
							</ul>
							<?php
						}
						?>
					</div>

					<!-- Card 3: Contact Info -->
					<div class="eafd-footer-widget-card">
						<h3 class="eafd-footer-title">
							<span class="eafd-title-dot"></span>
							ارتباط با ما
						</h3>
						<ul class="eafd-footer-contact-list">
							<?php if ( $footer_phone ) : ?>
								<li>
									<strong>تلفن تماس :</strong>
									<span><?php echo esc_html( $footer_phone ); ?></span>
								</li>
							<?php endif; ?>
							<?php if ( $footer_address ) : ?>
								<li>
									<strong>نشانی :</strong>
									<span><?php echo esc_html( $footer_address ); ?></span>
								</li>
							<?php endif; ?>
						</ul>
					</div>

					<!-- Card 4: Enamad / Trust Seal -->
					<div class="eafd-footer-widget-card eafd-enamad-card">
						<h3 class="eafd-footer-title">
							<span class="eafd-title-dot"></span>
							نماد اعتماد
						</h3>
						<div class="eafd-enamad-box">
							<?php if ( $footer_enamad ) : ?>
								<?php echo wp_kses_post( $footer_enamad ); ?>
							<?php else : ?>
								<div class="eafd-enamad-placeholder">
									<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#888" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
									<span>نماد اعتماد</span>
								</div>
							<?php endif; ?>
						</div>
					</div>

				</div><!-- .eafd-footer-grid -->

				<!-- Copyright Bar -->
				<div class="eafd-footer-copyright">
					<p><?php echo esc_html( $footer_copyright ); ?></p>
				</div>
			</div><!-- .eafd-footer-card -->
		</div><!-- .eafd-container -->
	</footer><!-- #colophon -->

	<!-- Floating Ajax Cart Button -->
	<a href="#" id="eafd-floating-cart-btn" class="eafd-floating-cart-btn" aria-label="سبد خرید">
		<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
			<circle cx="9" cy="21" r="1"></circle>
			<circle cx="20" cy="21" r="1"></circle>
			<path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
		</svg>
		<span class="eafd-floating-cart-count" id="eafd-cart-badge"><?php echo esc_html( eafd_convert_to_persian_digits( $cart_count ) ); ?></span>
	</a>

	<!-- Floating Cart Drawer Modal -->
	<div id="eafd-cart-drawer-overlay" class="eafd-cart-drawer-overlay"></div>
	<div id="eafd-cart-drawer" class="eafd-cart-drawer" role="dialog" aria-modal="true" aria-label="سبد خرید سریع">
		<div class="eafd-cart-drawer-header">
			<h3>سبد خرید شما</h3>
			<button id="eafd-cart-drawer-close" class="eafd-cart-drawer-close" aria-label="بستن">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
			</button>
		</div>
		<?php eafd_render_cart_drawer_content(); ?>
	</div>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
