<?php
/**
 * Kish Weather Widget Section Template (Final Specification Model)
 */

$options = get_option( 'kish_harmony_weather_options', array() );
if ( ! is_array( $options ) ) {
	$options = array();
}

$auto_api      = $options['auto_api'] ?? '1';
$title         = $options['title'] ?? 'آب و هوای لحظه‌ای کیش';
$subtitle      = $options['subtitle'] ?? 'وضعیت امروز جزیره زیبای کیش برای برنامه‌ریزی تفریحات شما';
$bg_image      = $options['bg_image'] ?? '';
$character_img = $options['character_img'] ?? '';

// Fetch Live Data if enabled
if ( $auto_api === '1' ) {
	$live_weather = kish_harmony_get_live_weather_data();
	if ( $live_weather ) {
		$temp_num    = $live_weather['temp_num'];
		$feels_num   = $live_weather['feels_num'];
		$status_text = $live_weather['status_text'];
		$humidity    = $live_weather['humidity'];
		$wind        = $live_weather['wind'];
		$sea_status  = $live_weather['sea_status'];
		$sunrise     = $live_weather['sunrise'];
		$sunset      = $live_weather['sunset'];
	}
}

if ( empty( $temp_num ) ) {
	$temp_num    = $options['temp_num'] ?? '32';
	$feels_num   = $options['feels_num'] ?? '34';
	$status_text = $options['status_text'] ?? 'صاف تا کمی ابری';
	$humidity    = $options['humidity'] ?? '65٪';
	$wind        = $options['wind'] ?? '12 km/h';
	$sea_status  = $options['sea_status'] ?? 'آرام و مناسب شنا';
	$sunrise     = $options['sunrise'] ?? '06:15';
	$sunset      = $options['sunset'] ?? '18:45';
}

$inline_bg_style = ! empty( $bg_image ) ? 'style="background-image: url(' . esc_url( $bg_image ) . ');"' : '';
?>

<div class="weather-widget-outer">
	<div class="container">

		<div class="weather-widget-container" <?php echo $inline_bg_style; ?> dir="rtl">

			<!-- 1. Header Section -->
			<div class="weather-header">
				<h2 class="weather-title">
					آب و هوای <span class="accent-orange">لحظه‌ای</span> کیش
				</h2>
				<p class="weather-subtitle">
					<?php echo esc_html( $subtitle ); ?> <span class="wave-symbol">≈</span>
				</p>
			</div>

			<!-- 2. Main Body (Desktop Flex Row / Mobile Column Reverse) -->
			<div class="weather-body">

				<!-- Right Side: Main Weather Card (Positioned Right in RTL) -->
				<div class="main-weather-card">
					<div class="temp-row">
						<span class="temp-number"><?php echo esc_html( $temp_num ); ?>°</span>
						<i class="fa-solid fa-sun weather-icon-sun"></i>
					</div>
					<div class="weather-condition"><?php echo esc_html( $status_text ); ?></div>
					<div class="feels-like">احساس واقعی: <?php echo esc_html( $feels_num ); ?>°C</div>
					<div class="location-tag">
						<i class="fa-solid fa-location-dot"></i> کیش، جزیره کیش
					</div>
				</div>

				<!-- Left Side: 3D Hippo Character Area (Positioned Left in RTL) -->
				<div class="character-area">
					<?php if ( ! empty( $character_img ) ) : ?>
						<img src="<?php echo esc_url( $character_img ); ?>" alt="کاراکتر آب و هوای کیش" class="hippo-character-img" />
					<?php else : ?>
						<div class="hippo-character-placeholder">
							<i class="fa-solid fa-hippo"></i>
						</div>
					<?php endif; ?>
				</div>

			</div>

			<!-- 3. Footer: 5 Small Glassmorphism Widgets -->
			<div class="weather-widgets-row">

				<div class="widget-item">
					<i class="fa-solid fa-droplet icon-humidity"></i>
					<span class="widget-label">میزان رطوبت</span>
					<span class="widget-value"><?php echo esc_html( $humidity ); ?></span>
					<span class="widget-note">رطوبت هوا</span>
				</div>

				<div class="widget-item">
					<i class="fa-solid fa-wind icon-wind"></i>
					<span class="widget-label">سرعت باد</span>
					<span class="widget-value"><?php echo esc_html( $wind ); ?></span>
					<span class="widget-note">وزش نسیم</span>
				</div>

				<div class="widget-item">
					<i class="fa-solid fa-water icon-sea"></i>
					<span class="widget-label">وضعیت دریا</span>
					<span class="widget-value"><?php echo esc_html( $sea_status ); ?></span>
					<span class="widget-note">خلیج فارس</span>
				</div>

				<div class="widget-item">
					<i class="fa-solid fa-sun icon-sunrise"></i>
					<span class="widget-label">طلوع خورشید</span>
					<span class="widget-value"><?php echo esc_html( $sunrise ); ?></span>
					<span class="widget-note">بامداد کیش</span>
				</div>

				<div class="widget-item">
					<i class="fa-solid fa-mountain-sun icon-sunset"></i>
					<span class="widget-label">غروب خورشید</span>
					<span class="widget-value"><?php echo esc_html( $sunset ); ?></span>
					<span class="widget-note">غروب طلایی</span>
				</div>

			</div>

		</div>

	</div>
</div>
