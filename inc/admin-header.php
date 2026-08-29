<?php
/**
 * Header Settings Page Callback
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kish_harmony_header_settings_page() {
	if ( isset( $_POST['kish_harmony_save_header'] ) && check_admin_referer( 'kish_harmony_header_nonce' ) ) {
		$header_data = array(
			'brand_name'        => sanitize_text_field( $_POST['brand_name'] ?? '' ),
			'logo_url'          => esc_url_raw( $_POST['logo_url'] ?? '' ),
			'header_menu_id'    => intval( $_POST['header_menu_id'] ?? 0 ),
			'enable_gtranslate' => isset( $_POST['enable_gtranslate'] ) ? '1' : '0',
		);

		update_option( 'kish_harmony_header_options', $header_data );
		echo '<div class="updated"><p>تنظیمات هدر با موفقیت ذخیره شد.</p></div>';
	}

	$options = get_option( 'kish_harmony_header_options', array(
		'brand_name'        => 'کیش هارمونی',
		'logo_url'          => '',
		'header_menu_id'    => 0,
		'enable_gtranslate' => '1',
	) );

	$menus = wp_get_nav_menus();
	?>
	<div class="wrap">
		<h1>تنظیمات هدر سایت</h1>
		<form method="post" action="">
			<?php wp_nonce_field( 'kish_harmony_header_nonce' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row">نام برند / سایت:</th>
					<td>
						<input type="text" name="brand_name" value="<?php echo esc_attr( $options['brand_name'] ); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row">آدرس لوگوی سفارشی (تصویر):</th>
					<td>
						<input type="text" name="logo_url" value="<?php echo esc_url( $options['logo_url'] ); ?>" class="large-text">
						<p class="description">در صورت خالی بودن، آیکون ✦ به همراه نام برند به عنوان لوگو قرار می‌گیرد.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">انتخاب فهرست هدر:</th>
					<td>
						<select name="header_menu_id">
							<option value="0">-- استفاده از فهرست پیش‌فرض هدر --</option>
							<?php foreach ( $menus as $menu ) : ?>
								<option value="<?php echo esc_attr( $menu->term_id ); ?>" <?php selected( $options['header_menu_id'], $menu->term_id ); ?>>
									<?php echo esc_html( $menu->name ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row">اتصال به افزونه ترجمه زبان GTranslate:</th>
					<td>
						<label>
							<input type="checkbox" name="enable_gtranslate" value="1" <?php checked( $options['enable_gtranslate'], '1' ); ?>>
							نمایش ویجت ترجمه زبان GTranslate در هدر
						</label>
					</td>
				</tr>
			</table>
			<p class="submit">
				<input type="submit" name="kish_harmony_save_header" class="button button-primary" value="ذخیره تنظیمات هدر">
			</p>
		</form>
	</div>
	<?php
}
