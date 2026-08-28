<?php
/**
 * WooCommerce Custom Theme Settings Submenu
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kish_harmony_add_wc_admin_submenu() {
	add_submenu_page(
		'kish-harmony-settings',
		'تنظیمات ووکامرس',
		'تنظیمات ووکامرس',
		'manage_options',
		'kish-harmony-woocommerce',
		'kish_harmony_wc_settings_render'
	);
}
add_action( 'admin_menu', 'kish_harmony_add_wc_admin_submenu', 25 );

function kish_harmony_wc_settings_render() {
	if ( isset( $_POST['kish_harmony_save_wc'] ) && check_admin_referer( 'kish_harmony_wc_nonce' ) ) {
		$btn_text = sanitize_text_field( $_POST['add_to_cart_text'] ?? '' );
		update_option( 'kish_harmony_add_to_cart_btn_text', $btn_text );
		echo '<div class="updated"><p>تنظیمات ووکامرس با موفقیت ذخیره شد.</p></div>';
	}

	$btn_text = get_option( 'kish_harmony_add_to_cart_btn_text', 'افزودن به سبد خرید' );
	?>
	<div class="wrap" style="direction: rtl; text-align: right;">
		<h1>تنظیمات اختصاصی ووکامرس قالب کیش هارمونی</h1>
		<form method="post" action="">
			<?php wp_nonce_field( 'kish_harmony_wc_nonce' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row"><label for="add_to_cart_text">متن دلخواه روی دکمه «افزودن به سبد خرید»:</label></th>
					<td>
						<input type="text" name="add_to_cart_text" id="add_to_cart_text" value="<?php echo esc_attr( $btn_text ); ?>" class="regular-text" placeholder="مثال: 🛒 افزودن به سبد خرید / رزرو بلیط">
					</td>
				</tr>
			</table>
			<p class="submit">
				<input type="submit" name="kish_harmony_save_wc" class="button button-primary" value="ذخیره تنظیمات ووکامرس">
			</p>
		</form>
	</div>
	<?php
}
