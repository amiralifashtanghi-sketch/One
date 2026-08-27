<?php
/**
 * Footer Settings Page Callback
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kish_harmony_footer_settings_page() {
	if ( isset( $_POST['kish_harmony_save_footer'] ) && check_admin_referer( 'kish_harmony_footer_nonce' ) ) {
		$footer_data = array(
			'footer_text'  => sanitize_text_field( $_POST['footer_text'] ?? '' ),
			'vip_text'     => sanitize_text_field( $_POST['vip_text'] ?? '' ),
			'phone_number' => sanitize_text_field( $_POST['phone_number'] ?? '' ),
			'enamad_code'  => wp_kses_post( $_POST['enamad_code'] ?? '' ),
		);

		update_option( 'kish_harmony_footer_options', $footer_data );
		echo '<div class="updated"><p>تنظیمات فوتر با موفقیت ذخیره شد.</p></div>';
	}

	$options = get_option( 'kish_harmony_footer_options', array(
		'footer_text'  => 'کیش هارمونی؛ مرجع رسمی رزرو خدمات و تفریحات جزیره کیش.',
		'vip_text'     => 'پشتیبانی ۲۴ ساعته VIP',
		'phone_number' => '076-44440000',
		'enamad_code'  => '',
	) );
	?>
	<div class="wrap">
		<h1>تنظیمات فوتر "جزیره‌ی آبی"</h1>
		<form method="post" action="">
			<?php wp_nonce_field( 'kish_harmony_footer_nonce' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row">متن درباره ما (فوتر):</th>
					<td>
						<textarea name="footer_text" rows="3" class="large-text"><?php echo esc_textarea( $options['footer_text'] ); ?></textarea>
					</td>
				</tr>
				<tr>
					<th scope="row">عنوان پشتیبانی VIP:</th>
					<td>
						<input type="text" name="vip_text" value="<?php echo esc_attr( $options['vip_text'] ); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row">شماره تلفن پشتیبانی:</th>
					<td>
						<input type="text" name="phone_number" value="<?php echo esc_attr( $options['phone_number'] ); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row">کد اینماد و نمادهای اعتماد (HTML / script / iframe):</th>
					<td>
						<textarea name="enamad_code" rows="5" class="large-text"><?php echo esc_textarea( $options['enamad_code'] ); ?></textarea>
					</td>
				</tr>
			</table>
			<p class="submit">
				<input type="submit" name="kish_harmony_save_footer" class="button button-primary" value="ذخیره تنظیمات فوتر">
			</p>
		</form>
	</div>
	<?php
}
