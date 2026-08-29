<?php
/**
 * Car Rental Section Admin Settings Page
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kish_harmony_add_car_rental_admin_menu() {
	add_submenu_page(
		'kish-harmony-settings',
		'تنظیمات بخش رنت خودرو',
		'تنظیمات رنت خودرو',
		'manage_options',
		'kish-harmony-car-rental',
		'kish_harmony_car_rental_settings_render'
	);
}
add_action( 'admin_menu', 'kish_harmony_add_car_rental_admin_menu', 20 );

function kish_harmony_car_rental_settings_render() {
	if ( isset( $_POST['car_rental_settings_submit'] ) && check_admin_referer( 'car_rental_settings_nonce' ) ) {
		update_option( 'car_rental_title', sanitize_text_field( $_POST['car_rental_title'] ?? '' ) );
		update_option( 'car_rental_subtitle', sanitize_text_field( $_POST['car_rental_subtitle'] ?? '' ) );
		update_option( 'car_rental_hint', sanitize_text_field( $_POST['car_rental_hint'] ?? '' ) );
		update_option( 'car_rental_btn_text', sanitize_text_field( $_POST['car_rental_btn_text'] ?? '' ) );

		echo '<div class="updated"><p>تنظیمات بخش رنت خودرو با موفقیت ذخیره شد.</p></div>';
	}

	$title    = get_option( 'car_rental_title', 'خودروهای ویژه رنت کیش' );
	$subtitle = get_option( 'car_rental_subtitle', 'سامانه جامع گردشگری و رنت خودرو در جزیره کیش' );
	$hint     = get_option( 'car_rental_hint', 'برای مشاهده بیشتر بکشید یا کلیک کنید' );
	$btn_text = get_option( 'car_rental_btn_text', 'رزرو آنلاین' );
	?>
	<div class="wrap" style="direction: rtl; text-align: right;">
		<h1>تنظیمات متن‌ها و دکمه‌های بخش رنت خودرو</h1>
		<form method="post" action="">
			<?php wp_nonce_field( 'car_rental_settings_nonce' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row"><label for="car_rental_title">عنوان اصلی بخش:</label></th>
					<td>
						<input type="text" name="car_rental_title" id="car_rental_title" value="<?php echo esc_attr( $title ); ?>" class="large-text">
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="car_rental_subtitle">شعار / زیرعنوان بخش:</label></th>
					<td>
						<input type="text" name="car_rental_subtitle" id="car_rental_subtitle" value="<?php echo esc_attr( $subtitle ); ?>" class="large-text">
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="car_rental_hint">متن راهنمای اسکرول:</label></th>
					<td>
						<input type="text" name="car_rental_hint" id="car_rental_hint" value="<?php echo esc_attr( $hint ); ?>" class="large-text">
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="car_rental_btn_text">متن دکمه رزرو کارت‌ها:</label></th>
					<td>
						<input type="text" name="car_rental_btn_text" id="car_rental_btn_text" value="<?php echo esc_attr( $btn_text ); ?>" class="regular-text">
					</td>
				</tr>
			</table>
			<p class="submit">
				<input type="submit" name="car_rental_settings_submit" class="button button-primary" value="ذخیره تغییرات رنت خودرو">
			</p>
		</form>
	</div>
	<?php
}
