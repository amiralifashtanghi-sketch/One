<?php
/**
 * Admin Panel Options for eafd-theme
 *
 * @package EAFD_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Option Key
 */
define( 'EAFD_OPTIONS_KEY', 'eafd_theme_options' );

/**
 * Helper to get theme options with defaults
 */
function eafd_get_option( $key, $default = '' ) {
	$options = get_option( EAFD_OPTIONS_KEY, array() );
	if ( is_array( $options ) && isset( $options[ $key ] ) && '' !== $options[ $key ] ) {
		return $options[ $key ];
	}
	return $default;
}

/**
 * Add Top Level Admin Menu "طراحی ظاهر سایت"
 */
function eafd_register_admin_menu() {
	add_menu_page(
		'طراحی ظاهر سایت',
		'طراحی ظاهر سایت',
		'manage_options',
		'eafd-appearance-settings',
		'eafd_render_admin_page',
		'dashicons-art',
		59
	);
}
add_action( 'admin_menu', 'eafd_register_admin_menu' );

/**
 * Enqueue Media Library Scripts for Admin Settings Page
 */
function eafd_admin_enqueue_scripts( $hook ) {
	if ( 'toplevel_page_eafd-appearance-settings' !== $hook ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );
}
add_action( 'admin_enqueue_scripts', 'eafd_admin_enqueue_scripts' );

/**
 * Register Settings & Sanitization
 */
function eafd_register_settings() {
	register_setting(
		'eafd_options_group',
		EAFD_OPTIONS_KEY,
		array(
			'sanitize_callback' => 'eafd_sanitize_options',
		)
	);
}
add_action( 'admin_init', 'eafd_register_settings' );

/**
 * Sanitization Callback
 */
function eafd_sanitize_options( $input ) {
	$output = array();

	// Color fields
	$output['primary_color'] = isset( $input['primary_color'] ) ? sanitize_hex_color( $input['primary_color'] ) : '#ff8a00';
	$output['bg_color']      = isset( $input['bg_color'] ) ? sanitize_hex_color( $input['bg_color'] ) : '#e3e7ee';
	$output['card_bg']       = isset( $input['card_bg'] ) ? sanitize_hex_color( $input['card_bg'] ) : '#ffffff';
	$output['text_color']    = isset( $input['text_color'] ) ? sanitize_hex_color( $input['text_color'] ) : '#2d3748';

	// Brand / Logo
	$output['brand_title'] = isset( $input['brand_title'] ) ? sanitize_text_field( $input['brand_title'] ) : 'محصولات ارگانیک سجاد برزویی';
	$output['logo_url']    = isset( $input['logo_url'] ) ? esc_url_raw( $input['logo_url'] ) : '';

	// Hero Banner
	$output['hero_banner_url']  = isset( $input['hero_banner_url'] ) ? esc_url_raw( $input['hero_banner_url'] ) : '';
	$output['hero_location_tag'] = isset( $input['hero_location_tag'] ) ? sanitize_text_field( $input['hero_location_tag'] ) : 'سبزوار - توحید شهر - فرزاندگان ۵';

	// Navigation Menus
	$output['hamburger_menu_id'] = isset( $input['hamburger_menu_id'] ) ? absint( $input['hamburger_menu_id'] ) : 0;
	$output['footer_menu_id']    = isset( $input['footer_menu_id'] ) ? absint( $input['footer_menu_id'] ) : 0;

	// Footer
	$output['footer_about']     = isset( $input['footer_about'] ) ? sanitize_textarea_field( $input['footer_about'] ) : 'فروشگاه محصولات ارگانیک سجاد برزویی آماده ثبت سفارش آنلاین شماست.';
	$output['footer_phone']     = isset( $input['footer_phone'] ) ? sanitize_text_field( $input['footer_phone'] ) : '۰۵۱ND۴۴۱۴۳۳۵';
	$output['footer_address']   = isset( $input['footer_address'] ) ? sanitize_text_field( $input['footer_address'] ) : 'سبزوار - توحید شهر - فرزاندگان ۵';
	$output['footer_enamad']    = isset( $input['footer_enamad'] ) ? wp_kses_post( $input['footer_enamad'] ) : '';
	$output['footer_copyright'] = isset( $input['footer_copyright'] ) ? sanitize_text_field( $input['footer_copyright'] ) : 'تمامی حقوق و مسئولیت این سایت متعلق به محصولات ارگانیک سجاد برزویی می باشد.';

	return $output;
}

/**
 * Render Admin Page
 */
function eafd_render_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$primary_color     = eafd_get_option( 'primary_color', '#ff8a00' );
	$bg_color          = eafd_get_option( 'bg_color', '#e3e7ee' );
	$card_bg           = eafd_get_option( 'card_bg', '#ffffff' );
	$text_color        = eafd_get_option( 'text_color', '#2d3748' );
	$brand_title       = eafd_get_option( 'brand_title', 'محصولات ارگانیک سجاد برزویی' );
	$logo_url          = eafd_get_option( 'logo_url', '' );
	$hero_banner_url   = eafd_get_option( 'hero_banner_url', '' );
	$hero_location_tag = eafd_get_option( 'hero_location_tag', 'سبزوار - توحید شهر - فرزاندگان ۵' );
	$hamburger_menu_id = eafd_get_option( 'hamburger_menu_id', 0 );
	$footer_menu_id    = eafd_get_option( 'footer_menu_id', 0 );
	$footer_about      = eafd_get_option( 'footer_about', 'فروشگاه محصولات ارگانیک سجاد برزویی آماده ثبت سفارش آنلاین شماست.' );

	$nav_menus = wp_get_nav_menus();
	$footer_phone      = eafd_get_option( 'footer_phone', '۰۵۱ND۴۴۱۴۳۳۵' );
	$footer_address    = eafd_get_option( 'footer_address', 'سبزوار - توحید شهر - فرزاندگان ۵' );
	$footer_enamad     = eafd_get_option( 'footer_enamad', '' );
	$footer_copyright  = eafd_get_option( 'footer_copyright', 'تمامی حقوق و مسئولیت این سایت متعلق به محصولات ارگانیک سجاد برزویی می باشد.' );
	?>
	<div class="wrap" style="direction: rtl; font-family: 'Vazirmatn', sans-serif;">
		<h1 style="margin-bottom: 20px;">تنظیمات طراحی ظاهر سایت (مدیریت قالب)</h1>
		<p style="color: #666; font-size: 14px; margin-bottom: 30px;">قالب آماده آکادمی برزویی ساخته شده به دست <a href="https://eafd.ir" target="_blank" style="color: #ff8a00; font-weight: bold; text-decoration: none;">eafd.ir</a></p>

		<?php settings_errors(); ?>

		<form method="post" action="options.php" style="background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); max-width: 900px;">
			<?php
			settings_fields( 'eafd_options_group' );
			do_settings_sections( 'eafd_options_group' );
			?>

			<!-- COLOR SETTINGS -->
			<div style="margin-bottom: 30px; border-bottom: 2px solid #f0f0f0; padding-bottom: 20px;">
				<h2 style="font-size: 18px; color: #111; margin-bottom: 15px;">🎨 تنظیمات رنگ‌بندی</h2>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><label for="primary_color">رنگ اصلی (دکمه‌ها و برچسب‌ها)</label></th>
						<td><input type="text" id="primary_color" name="<?php echo esc_attr( EAFD_OPTIONS_KEY ); ?>[primary_color]" value="<?php echo esc_attr( $primary_color ); ?>" class="eafd-color-picker" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="bg_color">رنگ پس‌زمینه سایت</label></th>
						<td><input type="text" id="bg_color" name="<?php echo esc_attr( EAFD_OPTIONS_KEY ); ?>[bg_color]" value="<?php echo esc_attr( $bg_color ); ?>" class="eafd-color-picker" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="card_bg">رنگ پس‌زمینه کارت‌ها</label></th>
						<td><input type="text" id="card_bg" name="<?php echo esc_attr( EAFD_OPTIONS_KEY ); ?>[card_bg]" value="<?php echo esc_attr( $card_bg ); ?>" class="eafd-color-picker" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="text_color">رنگ متون اصلی</label></th>
						<td><input type="text" id="text_color" name="<?php echo esc_attr( EAFD_OPTIONS_KEY ); ?>[text_color]" value="<?php echo esc_attr( $text_color ); ?>" class="eafd-color-picker" /></td>
					</tr>
				</table>
			</div>

			<!-- LOGO & BRAND SETTINGS -->
			<div style="margin-bottom: 30px; border-bottom: 2px solid #f0f0f0; padding-bottom: 20px;">
				<h2 style="font-size: 18px; color: #111; margin-bottom: 15px;">🏷️ برند و لوگو</h2>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><label for="brand_title">عنوان برند / فروشگاه</label></th>
						<td><input type="text" id="brand_title" name="<?php echo esc_attr( EAFD_OPTIONS_KEY ); ?>[brand_title]" value="<?php echo esc_attr( $brand_title ); ?>" class="regular-text" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="logo_url">تصویر لوگو / پروفایل هدر</label></th>
						<td>
							<input type="text" id="logo_url" name="<?php echo esc_attr( EAFD_OPTIONS_KEY ); ?>[logo_url]" value="<?php echo esc_attr( $logo_url ); ?>" class="regular-text" />
							<button type="button" class="button eafd-upload-btn" data-target="#logo_url">انتخاب / آپلود تصویر</button>
							<div id="logo_preview" style="margin-top: 10px;"><?php if ( $logo_url ) : ?><img src="<?php echo esc_url( $logo_url ); ?>" style="max-height: 60px; border-radius: 8px;" /><?php endif; ?></div>
						</td>
					</tr>
				</table>
			</div>

			<!-- HERO BANNER SETTINGS -->
			<div style="margin-bottom: 30px; border-bottom: 2px solid #f0f0f0; padding-bottom: 20px;">
				<h2 style="font-size: 18px; color: #111; margin-bottom: 15px;">🖼️ بنر اصلی (Hero Banner)</h2>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><label for="hero_banner_url">تصویر بنر اصلی</label></th>
						<td>
							<input type="text" id="hero_banner_url" name="<?php echo esc_attr( EAFD_OPTIONS_KEY ); ?>[hero_banner_url]" value="<?php echo esc_attr( $hero_banner_url ); ?>" class="regular-text" />
							<button type="button" class="button eafd-upload-btn" data-target="#hero_banner_url">انتخاب / آپلود بنر</button>
							<div id="hero_preview" style="margin-top: 10px;"><?php if ( $hero_banner_url ) : ?><img src="<?php echo esc_url( $hero_banner_url ); ?>" style="max-height: 100px; border-radius: 8px;" /><?php endif; ?></div>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="hero_location_tag">متن آدرس / برچسب روی بنر</label></th>
						<td><input type="text" id="hero_location_tag" name="<?php echo esc_attr( EAFD_OPTIONS_KEY ); ?>[hero_location_tag]" value="<?php echo esc_attr( $hero_location_tag ); ?>" class="regular-text" placeholder="مثال: سبزوار - توحید شهر - فرزاندگان ۵" /></td>
					</tr>
				</table>
			</div>

			<!-- MENU SETTINGS -->
			<div style="margin-bottom: 30px; border-bottom: 2px solid #f0f0f0; padding-bottom: 20px;">
				<h2 style="font-size: 18px; color: #111; margin-bottom: 15px;">🔗 فهرست‌های وردپرس (منوی همبرگری و فوتر)</h2>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><label for="hamburger_menu_id">فهرست منوی همبرگری</label></th>
						<td>
							<select id="hamburger_menu_id" name="<?php echo esc_attr( EAFD_OPTIONS_KEY ); ?>[hamburger_menu_id]">
								<option value="0">-- انتخاب فهرست وردپرس --</option>
								<?php if ( ! empty( $nav_menus ) ) : ?>
									<?php foreach ( $nav_menus as $menu ) : ?>
										<option value="<?php echo esc_attr( $menu->term_id ); ?>" <?php selected( $hamburger_menu_id, $menu->term_id ); ?>>
											<?php echo esc_html( $menu->name ); ?>
										</option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
							<p class="description">فهرستی که در کشوی منوی همبرگری (موبایل و دسکتاپ) نمایش داده می‌شود.</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="footer_menu_id">فهرست دسترسی سریع فوتر</label></th>
						<td>
							<select id="footer_menu_id" name="<?php echo esc_attr( EAFD_OPTIONS_KEY ); ?>[footer_menu_id]">
								<option value="0">-- انتخاب فهرست وردپرس --</option>
								<?php if ( ! empty( $nav_menus ) ) : ?>
									<?php foreach ( $nav_menus as $menu ) : ?>
										<option value="<?php echo esc_attr( $menu->term_id ); ?>" <?php selected( $footer_menu_id, $menu->term_id ); ?>>
											<?php echo esc_html( $menu->name ); ?>
										</option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
							<p class="description">فهرستی که در بخش «دسترسی سریع» فوتر نمایش داده می‌شود.</p>
						</td>
					</tr>
				</table>
			</div>

			<!-- FOOTER SETTINGS -->
			<div style="margin-bottom: 30px;">
				<h2 style="font-size: 18px; color: #111; margin-bottom: 15px;">🦶 اطلاعات فوتر</h2>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><label for="footer_about">متن درباره ما در فوتر</label></th>
						<td><textarea id="footer_about" name="<?php echo esc_attr( EAFD_OPTIONS_KEY ); ?>[footer_about]" rows="3" class="large-text"><?php echo esc_textarea( $footer_about ); ?></textarea></td>
					</tr>
					<tr>
						<th scope="row"><label for="footer_phone">شماره تلفن تماس</label></th>
						<td><input type="text" id="footer_phone" name="<?php echo esc_attr( EAFD_OPTIONS_KEY ); ?>[footer_phone]" value="<?php echo esc_attr( $footer_phone ); ?>" class="regular-text" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="footer_address">نشانی / آدرس</label></th>
						<td><input type="text" id="footer_address" name="<?php echo esc_attr( EAFD_OPTIONS_KEY ); ?>[footer_address]" value="<?php echo esc_attr( $footer_address ); ?>" class="regular-text" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="footer_enamad">کد HTML یا تصویر نماد اعتماد (ای‌نماد)</label></th>
						<td><textarea id="footer_enamad" name="<?php echo esc_attr( EAFD_OPTIONS_KEY ); ?>[footer_enamad]" rows="3" class="large-text"><?php echo esc_textarea( $footer_enamad ); ?></textarea></td>
					</tr>
					<tr>
						<th scope="row"><label for="footer_copyright">متن کپی‌رایت</label></th>
						<td><input type="text" id="footer_copyright" name="<?php echo esc_attr( EAFD_OPTIONS_KEY ); ?>[footer_copyright]" value="<?php echo esc_attr( $footer_copyright ); ?>" class="large-text" /></td>
					</tr>
				</table>
			</div>

			<?php submit_button( 'ذخیره تغییرات ظاهر سایت' ); ?>
		</form>
	</div>

	<script>
		jQuery(document).ready(function($) {
			$('.eafd-color-picker').wpColorPicker();

			$('.eafd-upload-btn').click(function(e) {
				e.preventDefault();
				var button = $(this);
				var targetInput = $(button.data('target'));

				var customUploader = wp.media({
					title: 'انتخاب تصویر',
					button: { text: 'استفاده از این تصویر' },
					multiple: false
				}).on('select', function() {
					var attachment = customUploader.state().get('selection').first().toJSON();
					targetInput.val(attachment.url);
				}).open();
			});
		});
	</script>
	<?php
}
