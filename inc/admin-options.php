<?php
/**
 * Theme Options Admin Panel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kish_harmony_add_admin_menu() {
	// Main Admin Menu
	add_menu_page(
		'تنظیمات ظاهری سایت',
		'تنظیمات ظاهری سایت',
		'manage_options',
		'kish-harmony-settings',
		'kish_harmony_general_settings_page',
		'dashicons-admin-customizer',
		59
	);

	// Submenus
	add_submenu_page(
		'kish-harmony-settings',
		'عمومی و عملکرد',
		'عمومی و عملکرد',
		'manage_options',
		'kish-harmony-settings',
		'kish_harmony_general_settings_page'
	);

	add_submenu_page(
		'kish-harmony-settings',
		'تنظیمات هدر',
		'تنظیمات هدر',
		'manage_options',
		'kish-harmony-header',
		'kish_harmony_header_settings_page'
	);

	add_submenu_page(
		'kish-harmony-settings',
		'خدمات ویژه شما',
		'خدمات ویژه شما',
		'manage_options',
		'kish-harmony-services',
		'kish_harmony_services_settings_page'
	);

	add_submenu_page(
		'kish-harmony-settings',
		'تنظیمات بنر سایت',
		'تنظیمات بنر سایت',
		'manage_options',
		'kish-harmony-banners',
		'kish_harmony_banner_settings_page'
	);

	add_submenu_page(
		'kish-harmony-settings',
		'تنظیمات جستجو',
		'تنظیمات جستجو',
		'manage_options',
		'kish-harmony-search',
		'kish_harmony_search_settings_page'
	);

	add_submenu_page(
		'kish-harmony-settings',
		'تنظیم تفریحات',
		'تنظیم تفریحات',
		'manage_options',
		'kish-harmony-categories',
		'kish_harmony_categories_settings_page'
	);

	add_submenu_page(
		'kish-harmony-settings',
		'پیشنهادهای ویژه',
		'پیشنهادهای ویژه',
		'manage_options',
		'kish-harmony-special-offers',
		'kish_harmony_special_offers_settings_page'
	);

	add_submenu_page(
		'kish-harmony-settings',
		'تنظیمات آب و هوا',
		'تنظیمات آب و هوا',
		'manage_options',
		'kish-harmony-weather',
		'kish_harmony_weather_settings_page'
	);

	add_submenu_page(
		'kish-harmony-settings',
		'گالری تصاویر',
		'گالری تصاویر',
		'manage_options',
		'kish-harmony-gallery',
		'kish_harmony_gallery_settings_page'
	);

	add_submenu_page(
		'kish-harmony-settings',
		'تنظیمات فوتر',
		'تنظیمات فوتر',
		'manage_options',
		'kish-harmony-footer',
		'kish_harmony_footer_settings_page'
	);
}
add_action( 'admin_menu', 'kish_harmony_add_admin_menu' );

/**
 * General & Performance Settings Page
 */
function kish_harmony_general_settings_page() {
	if ( isset( $_POST['kish_harmony_save_general'] ) && check_admin_referer( 'kish_harmony_general_nonce' ) ) {
		$section_order = isset( $_POST['section_order'] ) ? sanitize_text_field( $_POST['section_order'] ) : '';
		$disabled_sections = isset( $_POST['disabled_sections'] ) ? array_map( 'sanitize_text_field', $_POST['disabled_sections'] ) : array();
		$custom_blocks = isset( $_POST['custom_blocks'] ) ? $_POST['custom_blocks'] : array();

		// Sanitize custom blocks
		$sanitized_blocks = array();
		if ( is_array( $custom_blocks ) ) {
			foreach ( $custom_blocks as $block ) {
				$sanitized_blocks[] = array(
					'position' => sanitize_text_field( $block['position'] ?? '' ),
					'type'     => sanitize_text_field( $block['type'] ?? 'html' ),
					'content'  => wp_kses_post( $block['content'] ?? '' ),
				);
			}
		}

		$general_data = array(
			'section_order'     => $section_order,
			'disabled_sections' => $disabled_sections,
			'custom_blocks'     => $sanitized_blocks,
		);

		update_option( 'kish_harmony_general_options', $general_data );
		echo '<div class="updated"><p>تنظیمات عمومی با موفقیت ذخیره شد.</p></div>';
	}

	$options = get_option( 'kish_harmony_general_options', array(
		'section_order' => 'header,hero,services,banner,search,categories,special_offers,car_rental,kishpedia,weather,gallery,footer',
		'disabled_sections' => array(),
		'custom_blocks' => array(),
	) );

	// WooCommerce Status Test
	$wc_active = class_exists( 'WooCommerce' );
	// GTranslate Status Test
	$gtranslate_active = shortcode_exists( 'gtranslate' ) || defined( 'GTRANSLATE_MAIN_FILE' );

	$sections_list = array(
		'banner'         => 'بنر اصلی بالای صفحه (Hero Banner)',
		'services'       => 'دکمه‌های خدمات ویژه (۸ تایی)',
		'banner'         => 'بنر تبلیغاتی کیش هارمونی',
		'search'         => 'کادر جستجوی زنده (AJAX)',
		'categories'     => 'دسته‌بندی تفریحات کیش',
		'special_offers' => 'پیشنهادهای ویژه (ووکامرس)',
		'car_rental'     => 'معرفی سیستم رنت خودرو',
		'kishpedia'      => 'کیش‌پدیا (مقالات وبلاگ)',
		'weather'        => 'ویجت آب و هوای کیش',
		'gallery'        => 'گالری تصاویر مشتریان',
	);
	?>
	<div class="wrap">
		<h1>تنظیمات عمومی و عملکرد قالب کیش هارمونی</h1>

		<!-- Diagnostic Status Cards -->
		<div style="display: flex; gap: 20px; margin: 20px 0;">
			<div style="background: #fff; padding: 15px 20px; border-radius: 8px; border-right: 5px solid <?php echo $wc_active ? '#46b450' : '#dc3232'; ?>; flex: 1;">
				<h3 style="margin-top:0;">تست اتصال ووکامرس</h3>
				<p>وضعیت: <strong><?php echo $wc_active ? 'فعال و متصل' : 'غیرفعال (نیاز به نصب/فعال‌سازی افزونه WooCommerce دارد)'; ?></strong></p>
			</div>
			<div style="background: #fff; padding: 15px 20px; border-radius: 8px; border-right: 5px solid <?php echo $gtranslate_active ? '#46b450' : '#ffb900'; ?>; flex: 1;">
				<h3 style="margin-top:0;">تست اتصال مترجم GTranslate</h3>
				<p>وضعیت: <strong><?php echo $gtranslate_active ? 'شناسایی شد' : 'افزونه GTranslate نصب نیست (امکان ترجمه خودکار آماده است)'; ?></strong></p>
			</div>
		</div>

		<form method="post" action="">
			<?php wp_nonce_field( 'kish_harmony_general_nonce' ); ?>

			<h2>مدیریت و غیرفعال‌سازی بخش‌های ۱۰ گانه</h2>
			<table class="form-table">
				<tr>
					<th scope="row">بخش‌های فعال / غیرفعال:</th>
					<td>
						<?php foreach ( $sections_list as $key => $title ) : ?>
							<label style="display: block; margin-bottom: 8px;">
								<input type="checkbox" name="disabled_sections[]" value="<?php echo esc_attr( $key ); ?>" <?php checked( in_array( $key, (array) $options['disabled_sections'] ) ); ?>>
								غیرفعال کردن <strong><?php echo esc_html( $title ); ?></strong>
							</label>
						<?php endforeach; ?>
					</td>
				</tr>
			</table>

			<h2>افزودن بخش‌های سفارشی (HTML / PHP / Shortcode)</h2>
			<p>می‌توانید کدهای دلخواه خود را بین بخش‌های مختلف صفحه اصلی قرار دهید.</p>

			<div id="custom-blocks-container">
				<?php
				$custom_blocks = $options['custom_blocks'] ?? array();
				if ( ! empty( $custom_blocks ) ) :
					foreach ( $custom_blocks as $idx => $block ) :
				?>
					<div class="custom-block-row" style="background:#fff; padding:15px; margin-bottom:15px; border:1px solid #ccc; border-radius:6px;">
						<p>
							<label>موقعیت درج: </label>
							<select name="custom_blocks[<?php echo $idx; ?>][position]">
								<?php foreach ( $sections_list as $s_key => $s_title ) : ?>
									<option value="after_<?php echo $s_key; ?>" <?php selected( $block['position'], 'after_' . $s_key ); ?>>بعد از <?php echo esc_html( $s_title ); ?></option>
								<?php endforeach; ?>
							</select>

							<label style="margin-right:15px;">نوع کد: </label>
							<select name="custom_blocks[<?php echo $idx; ?>][type]">
								<option value="html" <?php selected( $block['type'], 'html' ); ?>>کد HTML / متنی</option>
								<option value="shortcode" <?php selected( $block['type'], 'shortcode' ); ?>>شورت‌کد (Shortcode)</option>
								<option value="php" <?php selected( $block['type'], 'php' ); ?>>کد PHP</option>
							</select>
						</p>
						<p>
							<textarea name="custom_blocks[<?php echo $idx; ?>][content]" rows="4" style="width:100%;"><?php echo esc_textarea( $block['content'] ); ?></textarea>
						</p>
					</div>
				<?php
					endforeach;
				endif;
				?>
			</div>

			<p class="submit">
				<input type="submit" name="kish_harmony_save_general" class="button button-primary" value="ذخیره تنظیمات عمومی">
			</p>
		</form>
	</div>
	<?php
}
