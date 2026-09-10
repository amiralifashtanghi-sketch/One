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
 * Add Top Level Admin Menu "طراحی ظاهر سایت" & Submenu "سلامت ووکامرس"
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

	add_submenu_page(
		'eafd-appearance-settings',
		'سلامت ووکامرس',
		'سلامت ووکامرس',
		'manage_options',
		'eafd-wc-health',
		'eafd_render_wc_health_page'
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
	$output['all_products_cat_img'] = isset( $input['all_products_cat_img'] ) ? esc_url_raw( $input['all_products_cat_img'] ) : '';

	// Hero Banner & Slider
	$output['hero_banner_url']  = isset( $input['hero_banner_url'] ) ? esc_url_raw( $input['hero_banner_url'] ) : '';
	$output['hero_location_tag'] = isset( $input['hero_location_tag'] ) ? sanitize_text_field( $input['hero_location_tag'] ) : 'سبزوار - توحید شهر - فرزاندگان ۵';

	$sanitized_slides = array();
	if ( isset( $input['hero_slides'] ) && is_array( $input['hero_slides'] ) ) {
		foreach ( $input['hero_slides'] as $slide ) {
			if ( ! is_array( $slide ) ) {
				continue;
			}
			$desktop_img = isset( $slide['desktop_img'] ) ? esc_url_raw( $slide['desktop_img'] ) : '';
			$mobile_img  = isset( $slide['mobile_img'] ) ? esc_url_raw( $slide['mobile_img'] ) : '';
			$title       = isset( $slide['title'] ) ? sanitize_text_field( $slide['title'] ) : '';
			$subtitle    = isset( $slide['subtitle'] ) ? sanitize_text_field( $slide['subtitle'] ) : '';
			$btn_text    = isset( $slide['btn_text'] ) ? sanitize_text_field( $slide['btn_text'] ) : '';
			$btn_link    = isset( $slide['btn_link'] ) ? esc_url_raw( $slide['btn_link'] ) : '';

			if ( ! empty( $desktop_img ) || ! empty( $mobile_img ) || ! empty( $title ) ) {
				$sanitized_slides[] = array(
					'desktop_img' => $desktop_img,
					'mobile_img'  => $mobile_img,
					'title'       => $title,
					'subtitle'    => $subtitle,
					'btn_text'    => $btn_text,
					'btn_link'    => $btn_link,
				);
			}
		}
	}
	$output['hero_slides'] = $sanitized_slides;

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
	$all_products_cat_img = eafd_get_option( 'all_products_cat_img', '' );
	$hero_banner_url   = eafd_get_option( 'hero_banner_url', '' );
	$hero_location_tag = eafd_get_option( 'hero_location_tag', 'سبزوار - توحید شهر - فرزاندگان ۵' );
	$hero_slides       = eafd_get_option( 'hero_slides', array() );
	if ( empty( $hero_slides ) && ! empty( $hero_banner_url ) ) {
		$hero_slides = array(
			array(
				'desktop_img' => $hero_banner_url,
				'mobile_img'  => $hero_banner_url,
				'title'       => '',
				'subtitle'    => $hero_location_tag,
				'btn_text'    => '',
				'btn_link'    => '#',
			)
		);
	}
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
					<tr>
						<th scope="row"><label for="all_products_cat_img">تصویر آیکون «همه محصولات»</label></th>
						<td>
							<input type="text" id="all_products_cat_img" name="<?php echo esc_attr( EAFD_OPTIONS_KEY ); ?>[all_products_cat_img]" value="<?php echo esc_attr( $all_products_cat_img ); ?>" class="regular-text" />
							<button type="button" class="button eafd-upload-btn" data-target="#all_products_cat_img">انتخاب / آپلود آیکون</button>
							<p class="description">تصویر دلخواه برای آیتم «همه محصولات» در بخش دسته‌بندی‌ها (در صورت خالی بودن، آیکون 🛍️ نمایش داده می‌شود).</p>
							<div id="all_products_preview" style="margin-top: 10px;"><?php if ( $all_products_cat_img ) : ?><img src="<?php echo esc_url( $all_products_cat_img ); ?>" style="max-height: 50px; border-radius: 8px;" /><?php endif; ?></div>
						</td>
					</tr>
				</table>
			</div>

			<!-- HERO BANNER & SLIDER SETTINGS -->
			<div style="margin-bottom: 30px; border-bottom: 2px solid #f0f0f0; padding-bottom: 20px;">
				<h2 style="font-size: 18px; color: #111; margin-bottom: 15px;">🖼️ اسلایدر و بنرهای اصلی هیرو (Hero Banner Slider)</h2>
				<p class="description" style="margin-bottom: 15px;">شما می‌توانید چندین اسلاید را برای اسلایدر اصلی سایت تعریف کنید. برای هر اسلاید تصویر دسکتاپ و موبایل، عنوان و دکمه لینک دلخواه قرار دهید.</p>

				<div id="eafd-slides-container">
					<?php if ( ! empty( $hero_slides ) ) : ?>
						<?php foreach ( $hero_slides as $index => $slide ) : ?>
							<div class="eafd-slide-item" style="background: #f9f9f9; border: 1px solid #e2e8f0; border-radius: 10px; padding: 15px; margin-bottom: 15px; position: relative;">
								<span class="eafd-remove-slide" style="position: absolute; left: 15px; top: 15px; color: #e53e3e; cursor: pointer; font-weight: bold;">❌ حذف اسلاید</span>
								<h3 style="margin-top: 0; font-size: 15px; color: #2d3748;">اسلاید شماره <span class="slide-num"><?php echo $index + 1; ?></span></h3>

								<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 10px;">
									<div>
										<label style="display: block; font-weight: bold; margin-bottom: 5px;">تصویر دسکتاپ:</label>
										<input type="text" class="regular-text slide-desktop-img" name="<?php echo esc_attr( EAFD_OPTIONS_KEY ); ?>[hero_slides][<?php echo $index; ?>][desktop_img]" value="<?php echo esc_attr( $slide['desktop_img'] ?? '' ); ?>" style="width: 100%;" />
										<button type="button" class="button eafd-slide-upload-btn" style="margin-top: 5px;">انتخاب تصویر دسکتاپ</button>
									</div>
									<div>
										<label style="display: block; font-weight: bold; margin-bottom: 5px;">تصویر موبایل (اختیاری):</label>
										<input type="text" class="regular-text slide-mobile-img" name="<?php echo esc_attr( EAFD_OPTIONS_KEY ); ?>[hero_slides][<?php echo $index; ?>][mobile_img]" value="<?php echo esc_attr( $slide['mobile_img'] ?? '' ); ?>" style="width: 100%;" />
										<button type="button" class="button eafd-slide-upload-btn" style="margin-top: 5px;">انتخاب تصویر موبایل</button>
									</div>
								</div>

								<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 10px;">
									<div>
										<label style="display: block; font-weight: bold; margin-bottom: 5px;">عنوان اسلاید:</label>
										<input type="text" name="<?php echo esc_attr( EAFD_OPTIONS_KEY ); ?>[hero_slides][<?php echo $index; ?>][title]" value="<?php echo esc_attr( $slide['title'] ?? '' ); ?>" style="width: 100%;" placeholder="مثال: جشنواره فروش ویژه تابستانه" />
									</div>
									<div>
										<label style="display: block; font-weight: bold; margin-bottom: 5px;">زیرعنوان / برچسب:</label>
										<input type="text" name="<?php echo esc_attr( EAFD_OPTIONS_KEY ); ?>[hero_slides][<?php echo $index; ?>][subtitle]" value="<?php echo esc_attr( $slide['subtitle'] ?? '' ); ?>" style="width: 100%;" placeholder="مثال: تا ۵۰٪ تخفیف روی تمامی محصولات" />
									</div>
								</div>

								<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
									<div>
										<label style="display: block; font-weight: bold; margin-bottom: 5px;">متن دکمه:</label>
										<input type="text" name="<?php echo esc_attr( EAFD_OPTIONS_KEY ); ?>[hero_slides][<?php echo $index; ?>][btn_text]" value="<?php echo esc_attr( $slide['btn_text'] ?? '' ); ?>" style="width: 100%;" placeholder="مثال: مشاهده و خرید" />
									</div>
									<div>
										<label style="display: block; font-weight: bold; margin-bottom: 5px;">لینک دکمه:</label>
										<input type="text" name="<?php echo esc_attr( EAFD_OPTIONS_KEY ); ?>[hero_slides][<?php echo $index; ?>][btn_link]" value="<?php echo esc_attr( $slide['btn_link'] ?? '' ); ?>" style="width: 100%;" placeholder="https://..." />
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>

				<button type="button" id="eafd-add-slide-btn" class="button button-primary" style="margin-top: 10px; font-weight: bold;">➕ افزودن اسلاید جدید</button>

				<input type="hidden" id="hero_banner_url" name="<?php echo esc_attr( EAFD_OPTIONS_KEY ); ?>[hero_banner_url]" value="<?php echo esc_attr( $hero_banner_url ); ?>" />
				<input type="hidden" id="hero_location_tag" name="<?php echo esc_attr( EAFD_OPTIONS_KEY ); ?>[hero_location_tag]" value="<?php echo esc_attr( $hero_location_tag ); ?>" />
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

	<?php
}

/**
 * Render WooCommerce Health Diagnostic Admin Page
 */
function eafd_render_wc_health_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$action_msg = '';

	// Handle repair trigger
	if ( isset( $_POST['eafd_repair_wc_pages'] ) && check_admin_referer( 'eafd_wc_repair_nonce' ) ) {
		$results = eafd_wc_pages()->auto_setup_pages();
		$count   = count( $results['created'] );
		$action_msg = '<div class="notice notice-success is-dismissible"><p>عملیات بازسازی برگه با موفقیت انجام شد. ' . esc_html( eafd_convert_to_persian_digits( $count ) ) . ' برگه جدید ساخته/اصلاح شد.</p></div>';
	}

	$health_data = eafd_wc_pages()->get_health_status();
	?>
	<div class="wrap" style="direction: rtl; font-family: 'Vazirmatn', sans-serif; max-width: 900px;">
		<h1 style="margin-bottom: 10px;">🩺 وضعیت سلامت برگه های ووکامرس</h1>
		<p style="color: #666; margin-bottom: 25px;">در این بخش می‌توانید از متصل بودن صحیح برگه‌های پایه ووکامرس (سبد خرید، تسویه حساب، حساب کاربری و فروشگاه) اطمینان حاصل کنید.</p>

		<?php echo $action_msg; ?>

		<div style="background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 25px;">
			<table class="wp-list-table widefat fixed striped" style="border: none;">
				<thead>
					<tr>
						<th style="font-weight: bold;">نام برگه ووکامرس</th>
						<th style="font-weight: bold;">شناسه برگه (ID)</th>
						<th style="font-weight: bold;">وضعیت اتصالات</th>
						<th style="font-weight: bold;">آدرس مستقیم (URL)</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $health_data as $key => $item ) : ?>
						<tr>
							<td style="font-weight: bold;"><?php echo esc_html( $item['title'] ); ?></td>
							<td><?php echo $item['page_id'] > 0 ? esc_html( eafd_convert_to_persian_digits( $item['page_id'] ) ) : '<span style="color:#e53e3e;">تعریف نشده</span>'; ?></td>
							<td>
								<?php if ( $item['exists'] ) : ?>
									<span style="background: #c6f6d5; color: #22543d; padding: 4px 10px; border-radius: 20px; font-weight: bold; font-size: 12px;">✅ فعال و متصل</span>
								<?php else : ?>
									<span style="background: #fed7d7; color: #742a2a; padding: 4px 10px; border-radius: 20px; font-weight: bold; font-size: 12px;">❌ مفقود / نامعتبر</span>
								<?php endif; ?>
							</td>
							<td><a href="<?php echo esc_url( $item['url'] ); ?>" target="_blank" style="color: var(--eafd-primary, #ff8a00);"><?php echo esc_html( $item['url'] ); ?></a></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<form method="post" action="">
			<?php wp_nonce_field( 'eafd_wc_repair_nonce' ); ?>
			<button type="submit" name="eafd_repair_wc_pages" class="button button-primary button-hero" style="font-weight: bold;">
				🔨 تعمیر و ساخت خودکار برگه‌های مفقود ووکامرس
			</button>
		</form>
	</div>
	<?php
}

function eafd_admin_scripts_inline() {
	$screen = get_current_screen();
	if ( ! $screen || false === strpos( $screen->id, 'eafd-appearance-settings' ) ) {
		return;
	}
	?>
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

			$(document).on('click', '.eafd-slide-upload-btn', function(e) {
				e.preventDefault();
				var button = $(this);
				var targetInput = button.siblings('input[type="text"]');

				var slideUploader = wp.media({
					title: 'انتخاب تصویر اسلاید',
					button: { text: 'استفاده از این تصویر' },
					multiple: false
				}).on('select', function() {
					var attachment = slideUploader.state().get('selection').first().toJSON();
					targetInput.val(attachment.url);
				}).open();
			});

			$('#eafd-add-slide-btn').click(function(e) {
				e.preventDefault();
				var container = $('#eafd-slides-container');
				var index = container.find('.eafd-slide-item').length;

				var html = '<div class="eafd-slide-item" style="background: #f9f9f9; border: 1px solid #e2e8f0; border-radius: 10px; padding: 15px; margin-bottom: 15px; position: relative;">' +
					'<span class="eafd-remove-slide" style="position: absolute; left: 15px; top: 15px; color: #e53e3e; cursor: pointer; font-weight: bold;">❌ حذف اسلاید</span>' +
					'<h3 style="margin-top: 0; font-size: 15px; color: #2d3748;">اسلاید شماره <span class="slide-num">' + (index + 1) + '</span></h3>' +
					'<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 10px;">' +
						'<div><label style="display: block; font-weight: bold; margin-bottom: 5px;">تصویر دسکتاپ:</label><input type="text" class="regular-text slide-desktop-img" name="eafd_theme_options[hero_slides][' + index + '][desktop_img]" value="" style="width: 100%;" /><button type="button" class="button eafd-slide-upload-btn" style="margin-top: 5px;">انتخاب تصویر دسکتاپ</button></div>' +
						'<div><label style="display: block; font-weight: bold; margin-bottom: 5px;">تصویر موبایل (اختیاری):</label><input type="text" class="regular-text slide-mobile-img" name="eafd_theme_options[hero_slides][' + index + '][mobile_img]" value="" style="width: 100%;" /><button type="button" class="button eafd-slide-upload-btn" style="margin-top: 5px;">انتخاب تصویر موبایل</button></div>' +
					'</div>' +
					'<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 10px;">' +
						'<div><label style="display: block; font-weight: bold; margin-bottom: 5px;">عنوان اسلاید:</label><input type="text" name="eafd_theme_options[hero_slides][' + index + '][title]" value="" style="width: 100%;" placeholder="مثال: جشنواره فروش ویژه تابستانه" /></div>' +
						'<div><label style="display: block; font-weight: bold; margin-bottom: 5px;">زیرعنوان / برچسب:</label><input type="text" name="eafd_theme_options[hero_slides][' + index + '][subtitle]" value="" style="width: 100%;" placeholder="مثال: تا ۵۰٪ تخفیف روی تمامی محصولات" /></div>' +
					'</div>' +
					'<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">' +
						'<div><label style="display: block; font-weight: bold; margin-bottom: 5px;">متن دکمه:</label><input type="text" name="eafd_theme_options[hero_slides][' + index + '][btn_text]" value="" style="width: 100%;" placeholder="مثال: مشاهده و خرید" /></div>' +
						'<div><label style="display: block; font-weight: bold; margin-bottom: 5px;">لینک دکمه:</label><input type="text" name="eafd_theme_options[hero_slides][' + index + '][btn_link]" value="" style="width: 100%;" placeholder="https://..." /></div>' +
					'</div>' +
				'</div>';

				container.append(html);
			});

			$(document).on('click', '.eafd-remove-slide', function() {
				$(this).closest('.eafd-slide-item').remove();
				$('#eafd-slides-container .eafd-slide-item').each(function(idx) {
					$(this).find('.slide-num').text(idx + 1);
					$(this).find('input').each(function() {
						var name = $(this).attr('name');
						if (name) {
							name = name.replace(/\[hero_slides\]\[\d+\]/, '[hero_slides][' + idx + ']');
							$(this).attr('name', name);
						}
					});
				});
			});
		});
	</script>
	<?php
}
add_action( 'admin_footer', 'eafd_admin_scripts_inline' );
