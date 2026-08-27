<?php
/**
 * Weather Settings Page Callback
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kish_harmony_weather_settings_page() {
	if ( isset( $_POST['kish_harmony_save_weather'] ) && check_admin_referer( 'kish_harmony_weather_nonce' ) ) {
		$weather_data = array(
			'title'       => sanitize_text_field( $_POST['title'] ?? '' ),
			'subtitle'    => sanitize_text_field( $_POST['subtitle'] ?? '' ),
			'temp'        => sanitize_text_field( $_POST['temp'] ?? '' ),
			'status_text' => sanitize_text_field( $_POST['status_text'] ?? '' ),
			'humidity'    => sanitize_text_field( $_POST['humidity'] ?? '' ),
			'wind'        => sanitize_text_field( $_POST['wind'] ?? '' ),
			'bg_image'    => esc_url_raw( $_POST['bg_image'] ?? '' ),
		);

		update_option( 'kish_harmony_weather_options', $weather_data );
		echo '<div class="updated"><p>تنظیمات ویجت آب و هوا با موفقیت ذخیره شد.</p></div>';
	}

	$options = get_option( 'kish_harmony_weather_options', array(
		'title'       => 'آب و هوای لحظه‌ای کیش',
		'subtitle'    => 'وضعیت امروز جزیره زیبای کیش برای برنامه‌ریزی تفریحات شما',
		'temp'        => '۲۸°C',
		'status_text' => 'آفتابی و مطلوب',
		'humidity'    => '۶۵٪',
		'wind'        => '۱۲ کلومتر بر ساعت',
		'bg_image'    => '',
	) );
	?>
	<div class="wrap">
		<h1>تنظیمات ویجت آب و هوای کیش</h1>
		<form method="post" action="">
			<?php wp_nonce_field( 'kish_harmony_weather_nonce' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row">عنوان ویجت:</th>
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
				<tr>
					<th scope="row">دمای هوا (درجه سانتی‌گراد):</th>
					<td>
						<input type="text" name="temp" value="<?php echo esc_attr( $options['temp'] ); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row">وضعیت هوا:</th>
					<td>
						<input type="text" name="status_text" value="<?php echo esc_attr( $options['status_text'] ); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row">میزان رطوبت:</th>
					<td>
						<input type="text" name="humidity" value="<?php echo esc_attr( $options['humidity'] ); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row">سرعت باد:</th>
					<td>
						<input type="text" name="wind" value="<?php echo esc_attr( $options['wind'] ); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row">تصویر پس‌زمینه ویجت (Glassmorphism BG):</th>
					<td>
						<input type="text" name="bg_image" value="<?php echo esc_url( $options['bg_image'] ); ?>" class="large-text">
					</td>
				</tr>
			</table>
			<p class="submit">
				<input type="submit" name="kish_harmony_save_weather" class="button button-primary" value="ذخیره تنظیمات آب و هوا">
			</p>
		</form>
	</div>
	<?php
}
