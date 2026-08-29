<?php
/**
 * Special Offers Admin Settings Page Callback
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kish_harmony_special_offers_settings_page() {
	if ( isset( $_POST['kish_harmony_save_special_offers'] ) && check_admin_referer( 'kish_harmony_special_offers_nonce' ) ) {
		$title    = sanitize_text_field( $_POST['title'] ?? '' );
		$subtitle = sanitize_text_field( $_POST['subtitle'] ?? '' );

		update_option( 'kish_harmony_special_offers_options', array(
			'title'    => $title,
			'subtitle' => $subtitle,
		) );
		echo '<div class="updated"><p>تنظیمات سرتیتر پیشنهادهای ویژه با موفقیت ذخیره شد.</p></div>';
	}

	$options = get_option( 'kish_harmony_special_offers_options', array(
		'title'    => 'پیشنهادهای ویژه',
		'subtitle' => 'تخفیف‌های استثنایی و محدود تورها و تفریحات کیش',
	) );
	?>
	<div class="wrap">
		<h1>تنظیمات بخش پیشنهادهای ویژه صفحه اصلی</h1>
		<form method="post" action="">
			<?php wp_nonce_field( 'kish_harmony_special_offers_nonce' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row">عنوان سرتیتر:</th>
					<td>
						<input type="text" name="title" value="<?php echo esc_attr( $options['title'] ); ?>" class="large-text">
					</td>
				</tr>
				<tr>
					<th scope="row">متن زیرعنوان:</th>
					<td>
						<input type="text" name="subtitle" value="<?php echo esc_attr( $options['subtitle'] ); ?>" class="large-text">
					</td>
				</tr>
			</table>
			<p class="submit">
				<input type="submit" name="kish_harmony_save_special_offers" class="button button-primary" value="ذخیره تنظیمات سرتیتر">
			</p>
		</form>
	</div>
	<?php
}
