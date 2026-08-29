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
	$transient_key = 'kish_harmony_live_weather_v2';
	$cached_data   = get_transient( $transient_key );

	if ( false !== $cached_data ) {
		return $cached_data;
	}

	// Latitude: 26.5333, Longitude: 53.9833 for Kish Island
	$api_url  = 'https://api.open-meteo.com/v1/forecast?latitude=26.5333&longitude=53.9833&current=temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m,apparent_temperature&daily=sunrise,sunset&timezone=Asia%2FTehran';
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
	$daily       = $data['daily'] ?? array();
	$temp_num    = round( $current['temperature_2m'] );
	$feels_num   = round( $current['apparent_temperature'] ?? $current['temperature_2m'] );
	$humidity    = $current['relative_humidity_2m'] . '٪';
	$wind_num    = round( $current['wind_speed_10m'] );
	$w_code      = $current['weather_code'] ?? 0;

	// Weather status translation mapping
	$status_text = 'صاف تا کمی ابری';
	if ( in_array( $w_code, array( 1, 2, 3 ), true ) ) {
		$status_text = 'صاف تا کمی ابری';
	} elseif ( in_array( $w_code, array( 45, 48 ), true ) ) {
		$status_text = 'مه‌آلود و شرجی';
	} elseif ( $w_code >= 51 && $w_code <= 67 ) {
		$status_text = 'بارش باران ملایم';
	} elseif ( $w_code >= 80 ) {
		$status_text = 'رگبار باران';
	}

	// Sea status calculation based on wind
	$sea_status = 'آرام و مناسب شنا';
	if ( $wind_num > 25 ) {
		$sea_status = 'مواح و طوفانی';
	} elseif ( $wind_num > 15 ) {
		$sea_status = 'کمی متلاطم';
	}

	// Sunrise & Sunset formatting
	$sunrise = '۰۶:۱۵';
	$sunset  = '۱۸:۴۵';
	if ( ! empty( $daily['sunrise'][0] ) ) {
		$sunrise = date( 'H:i', strtotime( $daily['sunrise'][0] ) );
	}
	if ( ! empty( $daily['sunset'][0] ) ) {
		$sunset = date( 'H:i', strtotime( $daily['sunset'][0] ) );
	}

	$result = array(
		'temp_num'    => $temp_num,
		'feels_num'   => $feels_num,
		'humidity'    => $humidity,
		'wind'        => $wind_num . ' km/h',
		'status_text' => $status_text,
		'sea_status'  => $sea_status,
		'sunrise'     => $sunrise,
		'sunset'      => $sunset,
	);

	// Cache for 1 hour (3600 seconds)
	set_transient( $transient_key, $result, 3600 );
	return $result;
}

function kish_harmony_weather_settings_page() {
	if ( isset( $_POST['kish_harmony_save_weather'] ) && check_admin_referer( 'kish_harmony_weather_nonce' ) ) {
		$weather_data = array(
			'title'         => sanitize_text_field( $_POST['title'] ?? '' ),
			'subtitle'      => sanitize_text_field( $_POST['subtitle'] ?? '' ),
			'auto_api'      => isset( $_POST['auto_api'] ) ? '1' : '0',
			'temp_num'      => sanitize_text_field( $_POST['temp_num'] ?? '' ),
			'feels_num'     => sanitize_text_field( $_POST['feels_num'] ?? '' ),
			'status_text'   => sanitize_text_field( $_POST['status_text'] ?? '' ),
			'humidity'      => sanitize_text_field( $_POST['humidity'] ?? '' ),
			'wind'          => sanitize_text_field( $_POST['wind'] ?? '' ),
			'sea_status'    => sanitize_text_field( $_POST['sea_status'] ?? '' ),
			'sunrise'       => sanitize_text_field( $_POST['sunrise'] ?? '' ),
			'sunset'        => sanitize_text_field( $_POST['sunset'] ?? '' ),
			'bg_image'      => esc_url_raw( $_POST['bg_image'] ?? '' ),
			'character_img' => esc_url_raw( $_POST['character_img'] ?? '' ),
		);

		update_option( 'kish_harmony_weather_options', $weather_data );
		delete_transient( 'kish_harmony_live_weather_v2' ); // Reset Cache
		echo '<div class="updated"><p>تنظیمات ویجت آب و هوا با موفقیت ذخیره شد.</p></div>';
	}

	$options = get_option( 'kish_harmony_weather_options', array() );
	if ( ! is_array( $options ) ) {
		$options = array();
	}

	$title         = $options['title'] ?? 'آب و هوای لحظه‌ای کیش';
	$subtitle      = $options['subtitle'] ?? 'وضعیت امروز جزیره زیبای کیش برای برنامه‌ریزی تفریحات شما';
	$auto_api      = $options['auto_api'] ?? '1';
	$temp_num      = $options['temp_num'] ?? '32';
	$feels_num     = $options['feels_num'] ?? '34';
	$status_text   = $options['status_text'] ?? 'صاف تا کمی ابری';
	$humidity      = $options['humidity'] ?? '65٪';
	$wind          = $options['wind'] ?? '12 km/h';
	$sea_status    = $options['sea_status'] ?? 'آرام و مناسب شنا';
	$sunrise       = $options['sunrise'] ?? '06:15';
	$sunset        = $options['sunset'] ?? '18:45';
	$bg_image      = $options['bg_image'] ?? '';
	$character_img = $options['character_img'] ?? '';

	$live_weather = kish_harmony_get_live_weather_data();
	?>
	<div class="wrap" style="direction: rtl; text-align: right;">
		<h1>تنظیمات جامع ویجت آب و هوای کیش</h1>

		<?php if ( $live_weather ) : ?>
			<div style="background:#e7f5ea; border-right:4px solid #46b450; padding:12px 18px; margin:15px 0; border-radius:6px;">
				<h3 style="margin-top:0;">وضعیت زنده آب و هوای کیش (API Open-Meteo):</h3>
				<p>دما: <strong><?php echo esc_html( $live_weather['temp_num'] ); ?>°C</strong> (احساس واقعی: <?php echo esc_html( $live_weather['feels_num'] ); ?>°C) | وضعیت: <strong><?php echo esc_html( $live_weather['status_text'] ); ?></strong> | رطوبت: <strong><?php echo esc_html( $live_weather['humidity'] ); ?></strong> | باد: <strong><?php echo esc_html( $live_weather['wind'] ); ?></strong> | دریا: <strong><?php echo esc_html( $live_weather['sea_status'] ); ?></strong></p>
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
					<th scope="row">عنوان اصلی ویجت:</th>
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
					<th scope="row">عکس پس‌زمینه ساحل/دریا (Glassmorphism BG):</th>
					<td>
						<input type="text" name="bg_image" value="<?php echo esc_url( $bg_image ); ?>" class="large-text" placeholder="https://...">
					</td>
				</tr>
				<tr>
					<th scope="row">عکس کاراکتر ۳ بعدی (اسب آبی):</th>
					<td>
						<input type="text" name="character_img" value="<?php echo esc_url( $character_img ); ?>" class="large-text" placeholder="https://...">
					</td>
				</tr>
				<tr>
					<th scope="row">دمای هوا (دستی):</th>
					<td>
						<input type="text" name="temp_num" value="<?php echo esc_attr( $temp_num ); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row">دمای احساس واقعی (دستی):</th>
					<td>
						<input type="text" name="feels_num" value="<?php echo esc_attr( $feels_num ); ?>" class="regular-text">
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
					<th scope="row">وضعیت دریا (دستی):</th>
					<td>
						<input type="text" name="sea_status" value="<?php echo esc_attr( $sea_status ); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row">زمان طلوع خورشید:</th>
					<td>
						<input type="text" name="sunrise" value="<?php echo esc_attr( $sunrise ); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row">زمان غروب خورشید:</th>
					<td>
						<input type="text" name="sunset" value="<?php echo esc_attr( $sunset ); ?>" class="regular-text">
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
