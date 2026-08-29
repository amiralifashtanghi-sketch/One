<?php
/**
 * KishPedia Admin Settings Submenu
 *
 * @package Kish_Harmony
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add KishPedia settings submenu page
 */
function kish_harmony_add_kishpedia_admin_menu() {
	add_submenu_page(
		'kish-harmony-settings',
		__( 'تنظیمات بنر کیش‌پدیا', 'kish-harmony' ),
		__( 'تنظیمات کیش‌پدیا', 'kish-harmony' ),
		'manage_options',
		'kish-harmony-kishpedia',
		'kish_harmony_kishpedia_settings_render'
	);
}
add_action( 'admin_menu', 'kish_harmony_add_kishpedia_admin_menu', 20 );

/**
 * Render KishPedia settings page
 */
function kish_harmony_kishpedia_settings_render() {
	if ( isset( $_POST['kishpedia_settings_submit'] ) && check_admin_referer( 'kishpedia_settings_nonce' ) ) {
		update_option( 'kishpedia_bg_img', sanitize_text_field( $_POST['kishpedia_bg_img'] ) );
		update_option( 'kishpedia_map_img', sanitize_text_field( $_POST['kishpedia_map_img'] ) );
		update_option( 'kishpedia_character_img', sanitize_text_field( $_POST['kishpedia_character_img'] ) );

		update_option( 'kishpedia_title_p1', sanitize_text_field( $_POST['kishpedia_title_p1'] ) );
		update_option( 'kishpedia_title_p2', sanitize_text_field( $_POST['kishpedia_title_p2'] ) );
		update_option( 'kishpedia_title_p3', sanitize_text_field( $_POST['kishpedia_title_p3'] ) );
		update_option( 'kishpedia_desc', sanitize_text_field( $_POST['kishpedia_desc'] ) );

		update_option( 'kishpedia_btn_left_text', sanitize_text_field( $_POST['kishpedia_btn_left_text'] ) );
		update_option( 'kishpedia_btn_left_link', sanitize_text_field( $_POST['kishpedia_btn_left_link'] ) );
		update_option( 'kishpedia_btn_right_text', sanitize_text_field( $_POST['kishpedia_btn_right_text'] ) );
		update_option( 'kishpedia_btn_right_link', sanitize_text_field( $_POST['kishpedia_btn_right_link'] ) );

		echo '<div class="updated"><p>' . __( 'تنظیمات بنر کیش‌پدیا با موفقیت ذخیره شد.', 'kish-harmony' ) . '</p></div>';
	}

	$bg_img        = get_option( 'kishpedia_bg_img', '' );
	$map_img       = get_option( 'kishpedia_map_img', '' );
	$character_img = get_option( 'kishpedia_character_img', '' );

	$title_p1      = get_option( 'kishpedia_title_p1', 'راهنمای سفر و برنامه‌ریزی برای' );
	$title_p2      = get_option( 'kishpedia_title_p2', 'جزیره کیش' );
	$title_p3      = get_option( 'kishpedia_title_p3', 'کامل' );
	$desc          = get_option( 'kishpedia_desc', 'همه چیز برای یک سفر بی‌نظیر، آسان و خاطره‌انگیز به جزیره کیش' );

	$btn_left_text  = get_option( 'kishpedia_btn_left_text', 'کیش پدیا' );
	$btn_left_link  = get_option( 'kishpedia_btn_left_link', get_post_type_archive_link( 'post' ) ?: '#' );
	$btn_right_text = get_option( 'kishpedia_btn_right_text', 'رزرو تفریحات جزیره' );
	$btn_right_link = get_option( 'kishpedia_btn_right_link', '#' );
	?>
	<div class="wrap" style="direction: rtl; text-align: right;">
		<h1><?php _e( 'تنظیمات بنر تبلیغاتی کیش‌پدیا', 'kish-harmony' ); ?></h1>
		<p><?php _e( 'مدیریت کامل متن‌ها، لینک‌ها، عکس پس‌زمینه، نقشه کیش و تصویر کاراکتر کوسه', 'kish-harmony' ); ?></p>

		<form method="post" action="">
			<?php wp_nonce_field( 'kishpedia_settings_nonce' ); ?>

			<table class="form-table">
				<tr>
					<th scope="row"><label for="kishpedia_bg_img"><?php _e( 'آدرس عکس پس‌زمینه (آسمان و دریا)', 'kish-harmony' ); ?></label></th>
					<td>
						<input type="text" name="kishpedia_bg_img" id="kishpedia_bg_img" value="<?php echo esc_attr( $bg_img ); ?>" class="large-text" placeholder="https://..." />
						<p class="description"><?php _e( 'عکس با کیفیت از آسمان و دریا. در صورت خالی بودن از رنگ پس‌زمینه پیش‌فرض استفاده می‌شود.', 'kish-harmony' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="kishpedia_map_img"><?php _e( 'آدرس عکس نقشه جزیره کیش (PNG)', 'kish-harmony' ); ?></label></th>
					<td>
						<input type="text" name="kishpedia_map_img" id="kishpedia_map_img" value="<?php echo esc_attr( $map_img ); ?>" class="large-text" placeholder="https://..." />
						<p class="description"><?php _e( 'تصویر PNG بدون پس‌زمینه (ابعاد پیشنهادی ۳۰۰x۳۰۰ پیکسل)', 'kish-harmony' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="kishpedia_character_img"><?php _e( 'آدرس عکس کاراکتر کوسه (PNG)', 'kish-harmony' ); ?></label></th>
					<td>
						<input type="text" name="kishpedia_character_img" id="kishpedia_character_img" value="<?php echo esc_attr( $character_img ); ?>" class="large-text" placeholder="https://..." />
						<p class="description"><?php _e( 'تصویر PNG شفاف کاراکتر (ابعاد پیشنهادی ۴۰۰x۴۵۰ پیکسل)', 'kish-harmony' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="kishpedia_title_p1"><?php _e( 'قسمت اول تیتر اصلی (آبی)', 'kish-harmony' ); ?></label></th>
					<td>
						<input type="text" name="kishpedia_title_p1" id="kishpedia_title_p1" value="<?php echo esc_attr( $title_p1 ); ?>" class="regular-text" />
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="kishpedia_title_p2"><?php _e( 'قسمت دوم تیتر اصلی (آبی تیره‌تر)', 'kish-harmony' ); ?></label></th>
					<td>
						<input type="text" name="kishpedia_title_p2" id="kishpedia_title_p2" value="<?php echo esc_attr( $title_p2 ); ?>" class="regular-text" />
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="kishpedia_title_p3"><?php _e( 'قسمت سوم تیتر اصلی (نارنجی)', 'kish-harmony' ); ?></label></th>
					<td>
						<input type="text" name="kishpedia_title_p3" id="kishpedia_title_p3" value="<?php echo esc_attr( $title_p3 ); ?>" class="regular-text" />
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="kishpedia_desc"><?php _e( 'متن توضیحی', 'kish-harmony' ); ?></label></th>
					<td>
						<textarea name="kishpedia_desc" id="kishpedia_desc" class="large-text" rows="3"><?php echo esc_textarea( $desc ); ?></textarea>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="kishpedia_btn_left_text"><?php _e( 'عنوان دکمه کیش‌پدیا (سفید)', 'kish-harmony' ); ?></label></th>
					<td>
						<input type="text" name="kishpedia_btn_left_text" id="kishpedia_btn_left_text" value="<?php echo esc_attr( $btn_left_text ); ?>" class="regular-text" />
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="kishpedia_btn_left_link"><?php _e( 'لینک دکمه کیش‌پدیا', 'kish-harmony' ); ?></label></th>
					<td>
						<input type="text" name="kishpedia_btn_left_link" id="kishpedia_btn_left_link" value="<?php echo esc_attr( $btn_left_link ); ?>" class="large-text" />
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="kishpedia_btn_right_text"><?php _e( 'عنوان دکمه رزرو (نارنجی)', 'kish-harmony' ); ?></label></th>
					<td>
						<input type="text" name="kishpedia_btn_right_text" id="kishpedia_btn_right_text" value="<?php echo esc_attr( $btn_right_text ); ?>" class="regular-text" />
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="kishpedia_btn_right_link"><?php _e( 'لینک دکمه رزرو', 'kish-harmony' ); ?></label></th>
					<td>
						<input type="text" name="kishpedia_btn_right_link" id="kishpedia_btn_right_link" value="<?php echo esc_attr( $btn_right_link ); ?>" class="large-text" />
					</td>
				</tr>
			</table>

			<p class="submit">
				<input type="submit" name="kishpedia_settings_submit" class="button button-primary" value="<?php _e( 'ذخیره تغییرات', 'kish-harmony' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}
