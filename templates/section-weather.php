<?php
/**
 * Weather Widget Section Template (Live Open-Meteo Integration)
 */
$options     = get_option( 'kish_harmony_weather_options', array() );
$title       = ! empty( $options['title'] ) ? $options['title'] : 'آب و هوای <span class="highlight-orange">لحظه‌ای</span> کیش';
$subtitle    = ! empty( $options['subtitle'] ) ? $options['subtitle'] : 'وضعیت امروز جزیره زیبای کیش برای برنامه‌ریزی تفریحات شما';
$auto_api    = isset( $options['auto_api'] ) ? $options['auto_api'] : '1';

$temp        = ! empty( $options['temp'] ) ? $options['temp'] : '۲۸°C';
$status_text = ! empty( $options['status_text'] ) ? $options['status_text'] : 'آفتابی و مطلوب';
$humidity    = ! empty( $options['humidity'] ) ? $options['humidity'] : '۶۵٪';
$wind        = ! empty( $options['wind'] ) ? $options['wind'] : '۱۲ کیلومتر بر ساعت';
$bg_image    = ! empty( $options['bg_image'] ) ? $options['bg_image'] : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80';

// Override with live API data if enabled
if ( $auto_api === '1' && function_exists( 'kish_harmony_get_live_weather_data' ) ) {
	$live_data = kish_harmony_get_live_weather_data();
	if ( $live_data ) {
		$temp        = $live_data['temp'];
		$status_text = $live_data['status_text'];
		$humidity    = $live_data['humidity'];
		$wind        = $live_data['wind'];
	}
}
?>

<div class="weather-widget-wrapper">
	<div class="container">
		<div class="weather-glass-container" style="background-image: linear-gradient(rgba(255,255,255,0.25), rgba(255,255,255,0.25)), url('<?php echo esc_url( $bg_image ); ?>');">
			<div class="weather-header">
				<h2><?php echo wp_kses_post( $title ); ?></h2>
				<p><?php echo esc_html( $subtitle ); ?></p>
			</div>

			<div class="weather-body-grid">
				<div class="weather-main-stat">
					<i class="fa-solid fa-sun weather-icon-sun"></i>
					<span class="weather-temp"><?php echo esc_html( $temp ); ?></span>
					<span class="weather-status-badge"><?php echo esc_html( $status_text ); ?></span>
				</div>

				<div class="weather-details-grid">
					<div class="weather-detail-card">
						<i class="fa-solid fa-droplet"></i>
						<span>رطوبت هوا</span>
						<strong><?php echo esc_html( $humidity ); ?></strong>
					</div>
					<div class="weather-detail-card">
						<i class="fa-solid fa-wind"></i>
						<span>سرعت باد</span>
						<strong><?php echo esc_html( $wind ); ?></strong>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
