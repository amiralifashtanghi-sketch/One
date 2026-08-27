<?php
/**
 * Weather Settings Page Callback & Open-Meteo Live API Helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fetch Live Kish Weather Data using free Open-Meteo API
 */
function kish_harmony_get_live_weather_data() {
	$transient_key = 'kish_harmony_live_weather';
	$cached_data   = get_transient( $transient_key );

	if ( false !== $cached_data ) {
		return $cached_data;
	}

	// Latitude: 26.5333, Longitude: 53.9833 for Kish Island (3 second fast timeout)
	$api_url  = 'https://api.open-meteo.com/v1/forecast?latitude=26.5333&longitude=53.9833&current=temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m&timezone=Asia%2FTehran';
	$response = wp_remote_get( $api_url, array( 'timeout' => 3 ) );

	if ( is_wp_error( $response ) ) {
		return false;
	}

	$body = wp_remote_retrieve_body( $response );
	$data = json_decode( $body, true );

	if ( empty( $data['current'] ) ) {
		return false;
	}

	$current     = $data['current'];
	$temp        = round( $current['temperature_2m'] ) . '°C';
	$humidity    = $current['relative_humidity_2m'] . '٪';
	$wind        = round( $current['wind_speed_10m'] ) . ' کیلومتر بر ساعت';
	$w_code      = $current['weather_code'] ?? 0;

	// Weather status translation mapping
	$status_text = 'آفتابی و صاف';
	if ( in_array( $w_code, array( 1, 2, 3 ), true ) ) {
		$status_text = 'کمی ابری';
	} elseif ( in_array( $w_code, array( 45, 48 ), true ) ) {
		$status_text = 'مه‌آلود';
	} elseif ( $w_code >= 51 && $w_code <= 67 ) {
		$status_text = 'بارش باران ملایم';
	} elseif ( $w_code >= 80 ) {
		$status_text = 'رگبار باران';
	}

	$result = array(
		'temp'        => $temp,
		'humidity'    => $humidity,
		'wind'        => $wind,
		'status_text' => $status_text,
	);

	// Cache for 1 hour (3600 seconds)
	set_transient( $transient_key, $result, 3600 );
	return $result;
}

function kish_harmony_weather_settings_page() {
	if ( isset( $_POST['kish_harmony_save_weather'] ) && check_admin_referer( 'kish_harmony_weather_nonce' ) ) {
		$weather_data = array(
			'title'       => sanitize_text_field( $_POST['title'] ?? '' ),
			'subtitle'    => sanitize_text_field( $_POST['subtitle'] ?? '' ),
			'auto_api'    => isset( $_POST['auto_api'] ) ? '1' : '0',
			'temp'        => sanitize_text_field( $_POST['temp'] ?? '' ),
			'status_text' => sanitize_text_field( $_POST['status_text'] ?? '' ),
			'humidity'    => sanitize_text_field( $_POST['humidity'] ?? '' ),
			'wind'        => sanitize_text_field( $_POST['wind'] ?? '' ),
			'bg_image'    => esc_url_raw( $_POST['bg_image'] ?? '' ),
		);

		update_option( 'kish_harmony_weather_options', $weather_data );
		delete_transient( 'kish_harmony_live_weather' ); // Reset Cache
		echo '<div class="updated"><p>تنظیمات ویجت آب و هوا با موفقیت ذخیره شد.</p></div>';
	}

	$options = get_option( 'kish_harmony_weather_options', array() );
	if ( ! is_array( $options ) ) {
		$options = array();
	}

	$title       = $options['title'] ?? 'آب و هوای لحظه‌ای کیش';
	$subtitle    = $options['subtitle'] ?? 'وضعیت امروز جزیره زیبای کیش برای برنامه‌ریزی تفریحات شما';
	$auto_api    = $options['auto_api'] ?? '1';
	$temp        = $options['temp'] ?? '۲۸°C';
	$status_text = $options['status_text'] ?? 'آفتابی و مطلوب';
	$humidity    = $options['humidity'] ?? '۶۵٪';
	$wind        = $options['wind'] ?? '۱۲ کیلومتر بر ساعت';
	$bg_image    = $options['bg_image'] ?? '';

	$live_weather = kish_harmony_get_live_weather_data();
	?>
	<div class="wrap">
		<h1>تنظیمات ویجت آب و هوای کیش</h1>

		<?php if ( $live_weather ) : ?>
			<div style="background:#e7f5ea; border-right:4px solid #46b450; padding:12px 18px; margin:15px 0; border-radius:6px;">
				<h3 style="margin-top:0;">وضعیت زنده آب و هوای کیش (از API Open-Meteo):</h3>
				<p>دما: <strong><?php echo esc_html( $live_weather['temp'] ); ?></strong> | وضعیت: <strong><?php echo esc_html( $live_weather['status_text'] ); ?></strong> | رطوبت: <strong><?php echo esc_html( $live_weather['humidity'] ); ?></strong> | سرعت باد: <strong><?php echo esc_html( $live_weather['wind'] ); ?></strong></p>
			</div>
		<?php endif; ?>

		<form method="post" action="">
			<?php wp_nonce_field( 'kish_harmony_weather_nonce' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row">دریافت خودکار و زنده آب و هوا:</th>
					<td>
						<label>
							<input type="checkbox" name="auto_api" value="1" <?php checked( $auto_api, '1' ); ?>>
							دریافت اتوماتیک و لحظه‌ای آب و هوای کیش (بدون نیاز به API Key)
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row">عنوان ویجت:</th>
					<td>
						<input type="text" name="title" value="<?php echo esc_attr( $title ); ?>" class="large-text">
					</td>
				</tr>
				<tr>
					<th scope="row">متن زیرعنوان:</th>
					<td>
						<input type="text" name="subtitle" value="<?php echo esc_attr( $subtitle ); ?>" class="large-text">
					</td>
				</tr>
				<tr>
					<th scope="row">دمای هوا (دستی):</th>
					<td>
						<input type="text" name="temp" value="<?php echo esc_attr( $temp ); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row">وضعیت هوا (دستی):</th>
					<td>
						<input type="text" name="status_text" value="<?php echo esc_attr( $status_text ); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row">میزان رطوبت (دستی):</th>
					<td>
						<input type="text" name="humidity" value="<?php echo esc_attr( $humidity ); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row">سرعت باد (دستی):</th>
					<td>
						<input type="text" name="wind" value="<?php echo esc_attr( $wind ); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row">تصویر پس‌زمینه ویجت (Glassmorphism BG):</th>
					<td>
						<input type="text" name="bg_image" value="<?php echo esc_url( $bg_image ); ?>" class="large-text">
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
