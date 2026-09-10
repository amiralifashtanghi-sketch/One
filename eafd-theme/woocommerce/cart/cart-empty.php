<?php
/**
 * Empty cart page
 *
 * @package EAFD_Theme
 */

defined( 'ABSPATH' ) || exit;

/*
 * @hooked woocommerce_empty_cart_message - 10
 */
do_action( 'woocommerce_cart_is_empty' );

if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
	<div class="eafd-empty-cart-page">
		<div class="eafd-empty-cart-card">
			<div class="eafd-empty-cart-icon">🛒</div>
			<h2>سبد خرید شما خالی است!</h2>
			<p>می‌توانید برای مشاهده و اضافه کردن محصولات به سبد خرید به فروشگاه مراجعه کنید.</p>
			<a class="eafd-btn eafd-btn-primary button wc-backward" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
				مشاهده محصولات فروشگاه
			</a>
		</div>
	</div>
<?php endif; ?>
