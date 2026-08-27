<?php
/**
 * Banner Settings Page Callback
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kish_harmony_banner_settings_page() {
	if ( isset( $_POST['kish_harmony_save_banner'] ) && check_admin_referer( 'kish_harmony_banner_nonce' ) ) {
		$banner_data = array(
			'title'       => sanitize_text_field( $_POST['title'] ?? '' ),
			'subtitle'    => sanitize_text_field( $_POST['subtitle'] ?? '' ),
			'bg_image'    => esc_url_raw( $_POST['bg_image'] ?? '' ),
			'shark_image' => esc_url_raw( $_POST['shark_image'] ?? '' ),
			'map_image'   => esc_url_raw( $_POST['map_image'] ?? '' ),
			'btn_text'    => sanitize_text_field( $_POST['btn_text'] ?? '' ),
			'btn_link'    => esc_url_raw( $_POST['btn_link'] ?? '' ),
		);

		update_option( 'kish_harmony_banner_options', $banner_data );
		echo '<div class="updated"><p>تنظیمات بنر تبلیغاتی با موفقیت ذخیره شد.</p></div>';
	}

	$options = get_option( 'kish_harmony_banner_options', array(
		'title'       => 'سفر به جزیره زیبای کیش با کیش هارمونی',
		'subtitle'    => 'رزرو آنلاین بهترین تورها، تفریحات دریایی و اجاره خودرو با پشتیبانی اختصاصی',
		'bg_image'    => '',
		'shark_image' => '',
		'map_image'   => '',
		'btn_text'    => 'مشاهده پیشنهادهای ویژه',
		'btn_link'    => '#special-offers',
	) );
	?>
	<div class="wrap">
		<h1>تنظیمات بنر سایت کیش هارمونی</h1>
		<form method="post" action="">
			<?php wp_nonce_field( 'kish_harmony_banner_nonce' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row">عنوان بنر:</th>
					<td>
						<input type="text" name="title" value="<?php echo esc_attr( $options['title'] ); ?>" class="large-text">
					</td>
				</tr>
				<tr>
					<th scope="row">زیرعنوان / توضیحات بنر:</th>
					<td>
						<input type="text" name="subtitle" value="<?php echo esc_attr( $options['subtitle'] ); ?>" class="large-text">
					</td>
				</tr>
				<tr>
					<th scope="row">متن دکمه:</th>
					<td>
						<input type="text" name="btn_text" value="<?php echo esc_attr( $options['btn_text'] ); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row">لینک دکمه:</th>
					<td>
						<input type="text" name="btn_link" value="<?php echo esc_attr( $options['btn_link'] ); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row">آدرس تصویر پس‌زمینه (آسمان/دریا):</th>
					<td>
						<input type="text" name="bg_image" value="<?php echo esc_url( $options['bg_image'] ); ?>" class="large-text">
					</td>
				</tr>
				<tr>
					<th scope="row">آدرس تصویر کاراکتر کوسه (PNG بدون بک‌گراند):</th>
					<td>
						<input type="text" name="shark_image" value="<?php echo esc_url( $options['shark_image'] ); ?>" class="large-text">
					</td>
				</tr>
				<tr>
					<th scope="row">آدرس تصویر نقشه جزیره (PNG):</th>
					<td>
						<input type="text" name="map_image" value="<?php echo esc_url( $options['map_image'] ); ?>" class="large-text">
					</td>
				</tr>
			</table>
			<p class="submit">
				<input type="submit" name="kish_harmony_save_banner" class="button button-primary" value="ذخیره تنظیمات بنر">
			</p>
		</form>
	</div>
	<?php
}
