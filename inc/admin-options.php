<?php
/**
 * General & Performance Settings Page Callback (With Move Up/Down Controls)
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

function kish_harmony_general_settings_page() {
	if ( isset( $_POST['kish_harmony_save_general'] ) && check_admin_referer( 'kish_harmony_general_nonce' ) ) {
		$section_order     = isset( $_POST['section_order'] ) ? array_map( 'sanitize_text_field', $_POST['section_order'] ) : array();
		$disabled_sections = isset( $_POST['disabled_sections'] ) ? array_map( 'sanitize_text_field', $_POST['disabled_sections'] ) : array();
		$custom_blocks     = isset( $_POST['custom_blocks'] ) ? $_POST['custom_blocks'] : array();

		// Sanitize custom blocks
		$sanitized_blocks = array();
		if ( is_array( $custom_blocks ) ) {
			foreach ( $custom_blocks as $block ) {
				if ( ! empty( $block['content'] ) ) {
					$sanitized_blocks[] = array(
						'position' => sanitize_text_field( $block['position'] ?? '' ),
						'type'     => sanitize_text_field( $block['type'] ?? 'html' ),
						'content'  => wp_kses_post( $block['content'] ?? '' ),
					);
				}
			}
		}

		$general_data = array(
			'section_order'     => implode( ',', $section_order ),
			'disabled_sections' => $disabled_sections,
			'custom_blocks'     => $sanitized_blocks,
		);

		update_option( 'kish_harmony_general_options', $general_data );
		echo '<div class="updated"><p>تنظیمات عمومی با موفقیت ذخیره شد.</p></div>';
	}

	$all_sections = array(
		'banner'         => 'بنر اصلی بالای صفحه (Hero Banner)',
		'services'       => 'دکمه‌های خدمات ویژه (۸ تایی)',
		'search'         => 'کادر جستجوی زنده (AJAX)',
		'categories'     => 'دسته‌بندی تفریحات کیش',
		'special_offers' => 'پیشنهادهای ویژه (ووکامرس)',
		'car_rental'     => 'معرفی سیستم رنت خودرو',
		'kishpedia'      => 'کیش‌پدیا (مقالات وبلاگ)',
		'weather'        => 'ویجت آب و هوای کیش',
		'gallery'        => 'گالری تصاویر مشتریان',
	);

	$options = get_option( 'kish_harmony_general_options', array() );
	if ( ! is_array( $options ) ) {
		$options = array();
	}

	$disabled_sections = isset( $options['disabled_sections'] ) && is_array( $options['disabled_sections'] ) ? $options['disabled_sections'] : array();
	$order_str         = isset( $options['section_order'] ) ? $options['section_order'] : implode( ',', array_keys( $all_sections ) );
	$saved_order       = array_filter( explode( ',', $order_str ) );

	foreach ( array_keys( $all_sections ) as $sec_key ) {
		if ( ! in_array( $sec_key, $saved_order, true ) ) {
			$saved_order[] = $sec_key;
		}
	}

	$wc_active         = class_exists( 'WooCommerce' );
	$gtranslate_active = shortcode_exists( 'gtranslate' ) || defined( 'GTRANSLATE_MAIN_FILE' );
	?>
	<div class="wrap">
		<h1>تنظیمات عمومی و عملکرد قالب کیش هارمونی</h1>

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

			<h2>چیدمان و فعال/غیرفعال‌سازی بخش‌های صفحه اصلی</h2>
			<p>با دکمه‌های <strong>▲ بالا</strong> و <strong>▼ پایین</strong> کنار هر بخش می‌توانید چیدمان نمایش بخش‌ها در صفحه نخست را تغییر دهید.</p>

			<ul id="section-order-list" style="max-width:650px; background:#fff; border:1px solid #ccc; border-radius:8px; padding:10px;">
				<?php foreach ( $saved_order as $key ) :
					if ( ! isset( $all_sections[ $key ] ) ) continue;
					$title = $all_sections[ $key ];
					$is_disabled = in_array( $key, $disabled_sections, true );
				?>
					<li class="section-order-item" style="display:flex; justify-content:space-between; align-items:center; padding:12px; border-bottom:1px solid #eee; background:#f9f9f9; margin-bottom:6px; border-radius:6px;">
						<input type="hidden" name="section_order[]" value="<?php echo esc_attr( $key ); ?>">
						<span><strong><?php echo esc_html( $title ); ?></strong></span>
						<div style="display:flex; align-items:center; gap:10px;">
							<button type="button" class="button move-up-btn">▲ بالا</button>
							<button type="button" class="button move-down-btn">▼ پایین</button>
							<label style="color:red; margin-right:10px;">
								<input type="checkbox" name="disabled_sections[]" value="<?php echo esc_attr( $key ); ?>" <?php checked( $is_disabled ); ?>>
								غیرفعال
							</label>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>

			<h2>افزودن بخش‌های سفارشی (HTML / PHP / Shortcode)</h2>
			<p>می‌توانید کدهای دلخواه خود را بین بخش‌های مختلف صفحه اصلی قرار دهید.</p>

			<div id="custom-blocks-container">
				<?php
				$custom_blocks = $options['custom_blocks'] ?? array();
				if ( ! empty( $custom_blocks ) && is_array( $custom_blocks ) ) :
					foreach ( $custom_blocks as $idx => $block ) :
				?>
					<div class="custom-block-row" style="background:#fff; padding:15px; margin-bottom:15px; border:1px solid #ccc; border-radius:6px; position:relative;">
						<button type="button" class="button remove-custom-block" style="position:absolute; top:10px; left:10px; color:red; border-color:red;">حذف این بلوک</button>
						<p>
							<label>موقعیت درج: </label>
							<select name="custom_blocks[<?php echo $idx; ?>][position]">
								<?php foreach ( $all_sections as $s_key => $s_title ) : ?>
									<option value="after_<?php echo $s_key; ?>" <?php selected( $block['position'] ?? '', 'after_' . $s_key ); ?>>بعد از <?php echo esc_html( $s_title ); ?></option>
								<?php endforeach; ?>
							</select>

							<label style="margin-right:15px;">نوع کد: </label>
							<select name="custom_blocks[<?php echo $idx; ?>][type]">
								<option value="html" <?php selected( $block['type'] ?? 'html', 'html' ); ?>>کد HTML / متنی</option>
								<option value="shortcode" <?php selected( $block['type'] ?? '', 'shortcode' ); ?>>شورت‌کد (Shortcode)</option>
								<option value="php" <?php selected( $block['type'] ?? '', 'php' ); ?>>کد PHP</option>
							</select>
						</p>
						<p>
							<textarea name="custom_blocks[<?php echo $idx; ?>][content]" rows="4" style="width:100%;"><?php echo esc_textarea( $block['content'] ?? '' ); ?></textarea>
						</p>
					</div>
				<?php
					endforeach;
				endif;
				?>
			</div>

			<button type="button" id="add-custom-block-btn" class="button" style="margin-bottom:20px;">+ افزودن بلوک سفارشی جدید</button>

			<p class="submit">
				<input type="submit" name="kish_harmony_save_general" class="button button-primary" value="ذخیره تنظیمات عمومی">
			</p>
		</form>
	</div>

	<script>
	document.addEventListener('DOMContentLoaded', function() {
		// Move Up/Down controls
		const orderList = document.getElementById('section-order-list');
		if (orderList) {
			orderList.addEventListener('click', function(e) {
				const row = e.target.closest('.section-order-item');
				if (!row) return;

				if (e.target.classList.contains('move-up-btn')) {
					const prev = row.previousElementSibling;
					if (prev) {
						orderList.insertBefore(row, prev);
					}
				} else if (e.target.classList.contains('move-down-btn')) {
					const next = row.nextElementSibling;
					if (next) {
						orderList.insertBefore(next, row);
					}
				}
			});
		}

		// Custom Block Repeater
		const container = document.getElementById('custom-blocks-container');
		const addBtn = document.getElementById('add-custom-block-btn');

		if (addBtn && container) {
			addBtn.addEventListener('click', function() {
				const idx = Date.now();
				const html = `
					<div class="custom-block-row" style="background:#fff; padding:15px; margin-bottom:15px; border:1px solid #ccc; border-radius:6px; position:relative;">
						<button type="button" class="button remove-custom-block" style="position:absolute; top:10px; left:10px; color:red; border-color:red;">حذف این بلوک</button>
						<p>
							<label>موقعیت درج: </label>
							<select name="custom_blocks[${idx}][position]">
								<?php foreach ( $all_sections as $s_key => $s_title ) : ?>
									<option value="after_<?php echo $s_key; ?>">بعد از <?php echo esc_html( $s_title ); ?></option>
								<?php endforeach; ?>
							</select>

							<label style="margin-right:15px;">نوع کد: </label>
							<select name="custom_blocks[${idx}][type]">
								<option value="html">کد HTML / متنی</option>
								<option value="shortcode">شورت‌کد (Shortcode)</option>
								<option value="php">کد PHP</option>
							</select>
						</p>
						<p>
							<textarea name="custom_blocks[${idx}][content]" rows="4" style="width:100%;" placeholder="کد یا شورت‌کد خود را اینجا وارد کنید..."></textarea>
						</p>
					</div>
				`;
				container.insertAdjacentHTML('beforeend', html);
			});

			container.addEventListener('click', function(e) {
				if (e.target.classList.contains('remove-custom-block')) {
					e.target.closest('.custom-block-row').remove();
				}
			});
		}
	});
	</script>
	<?php
}
