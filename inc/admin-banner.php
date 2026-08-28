<?php
/**
 * Banner Settings Page Callback (Multi-Banner Slider Options)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kish_harmony_banner_settings_page() {
	if ( isset( $_POST['kish_harmony_save_banner'] ) && check_admin_referer( 'kish_harmony_banner_nonce' ) ) {
		$auto_scroll   = isset( $_POST['auto_scroll'] ) ? '1' : '0';
		$scroll_speed  = intval( $_POST['scroll_speed'] ?? 5 );
		$banners_input = isset( $_POST['banners'] ) ? $_POST['banners'] : array();

		$sanitized_banners = array();
		if ( is_array( $banners_input ) ) {
			foreach ( $banners_input as $b ) {
				if ( ! empty( $b['title'] ) || ! empty( $b['bg_image'] ) ) {
					$sanitized_banners[] = array(
						'title'       => sanitize_text_field( $b['title'] ?? '' ),
						'subtitle'    => sanitize_text_field( $b['subtitle'] ?? '' ),
						'bg_image'    => esc_url_raw( $b['bg_image'] ?? '' ),
						'shark_image' => esc_url_raw( $b['shark_image'] ?? '' ),
						'map_image'   => esc_url_raw( $b['map_image'] ?? '' ),
						'btn_text'    => sanitize_text_field( $b['btn_text'] ?? '' ),
						'btn_link'    => esc_url_raw( $b['btn_link'] ?? '' ),
					);
				}
			}
		}

		$banner_data = array(
			'auto_scroll'  => $auto_scroll,
			'scroll_speed' => $scroll_speed,
			'banners'      => $sanitized_banners,
		);

		update_option( 'kish_harmony_banner_options', $banner_data );
		echo '<div class="updated"><p>تنظیمات بنرها با موفقیت ذخیره شد.</p></div>';
	}

	$options = get_option( 'kish_harmony_banner_options', array() );
	if ( ! is_array( $options ) ) {
		$options = array();
	}

	$auto_scroll  = isset( $options['auto_scroll'] ) ? $options['auto_scroll'] : '1';
	$scroll_speed = isset( $options['scroll_speed'] ) ? intval( $options['scroll_speed'] ) : 5;
	$banners      = ! empty( $options['banners'] ) && is_array( $options['banners'] ) ? $options['banners'] : array(
		array(
			'title'       => 'سفر به جزیره زیبای کیش با کیش هارمونی',
			'subtitle'    => 'رزرو آنلاین بهترین تورها، تفریحات دریایی و اجاره خودرو با پشتیبانی اختصاصی',
			'bg_image'    => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1300&q=80',
			'shark_image' => '',
			'map_image'   => '',
			'btn_text'    => 'مشاهده پیشنهادهای ویژه',
			'btn_link'    => '#special-offers',
		),
	);
	?>
	<div class="wrap" style="direction: rtl; text-align: right;">
		<h1>تنظیمات بنرهای تبلیغاتی کیش هارمونی (اسلایدر)</h1>
		<div class="notice notice-info" style="padding:10px 15px; margin:15px 0;">
			<p><strong>💡 راهنمای ابعاد تصاویر:</strong> جهت نمایش بدون برش و یکسان در تمام دستگاه‌ها (موبایل و دسکتاپ)، ابعاد استاندارد پیشنهادی <strong>۱۹۲۰ در ۱۰۸۰ پیکسل (نسبت ۱۶:۹)</strong> یا حداقل <strong>۱۲۸۰ در ۷۲۰ پیکسل</strong> می‌باشد.</p>
		</div>
		<form method="post" action="">
			<?php wp_nonce_field( 'kish_harmony_banner_nonce' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row">اسکرول اتوماتیک اسلایدها:</th>
					<td>
						<label>
							<input type="checkbox" name="auto_scroll" value="1" <?php checked( $auto_scroll, '1' ); ?>>
							فعال بودن اسکرول خودکار بنرها
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row">زمان تعویض هر بنر (ثانیه):</th>
					<td>
						<input type="number" name="scroll_speed" value="<?php echo esc_attr( $scroll_speed ); ?>" class="small-text" min="1" max="60"> ثانیه
					</td>
				</tr>
			</table>

			<h2>مدیریت بنرها</h2>
			<div id="banners-repeater">
				<?php foreach ( $banners as $idx => $b ) :
					$title       = $b['title'] ?? '';
					$subtitle    = $b['subtitle'] ?? '';
					$btn_text    = $b['btn_text'] ?? '';
					$btn_link    = $b['btn_link'] ?? '';
					$bg_image    = $b['bg_image'] ?? '';
					$shark_image = $b['shark_image'] ?? '';
					$map_image   = $b['map_image'] ?? '';
				?>
					<div class="banner-item-row" style="background:#fff; border:1px solid #ccc; padding:15px; margin-bottom:15px; border-radius:8px; position:relative;">
						<button type="button" class="button remove-banner-btn" style="position:absolute; top:10px; left:10px; color:red; border-color:red;">حذف این بنر</button>
						<h3 style="margin-top:0;">بنر #<?php echo $idx + 1; ?></h3>
						<div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
							<div>
								<label>عنوان بنر:</label><br>
								<input type="text" name="banners[<?php echo $idx; ?>][title]" value="<?php echo esc_attr( $title ); ?>" class="large-text">
							</div>
							<div>
								<label>زیرعنوان / توضیحات:</label><br>
								<input type="text" name="banners[<?php echo $idx; ?>][subtitle]" value="<?php echo esc_attr( $subtitle ); ?>" class="large-text">
							</div>
							<div>
								<label>متن دکمه:</label><br>
								<input type="text" name="banners[<?php echo $idx; ?>][btn_text]" value="<?php echo esc_attr( $btn_text ); ?>" class="regular-text">
							</div>
							<div>
								<label>لینک دکمه:</label><br>
								<input type="text" name="banners[<?php echo $idx; ?>][btn_link]" value="<?php echo esc_url( $btn_link ); ?>" class="regular-text">
							</div>
							<div>
								<label>آدرس تصویر پس‌زمینه (BG Image):</label><br>
								<input type="text" name="banners[<?php echo $idx; ?>][bg_image]" value="<?php echo esc_url( $bg_image ); ?>" class="large-text">
							</div>
							<div>
								<label>آدرس تصویر کاراکتر کوسه (PNG بدون بک‌گراند):</label><br>
								<input type="text" name="banners[<?php echo $idx; ?>][shark_image]" value="<?php echo esc_url( $shark_image ); ?>" class="large-text">
							</div>
							<div>
								<label>آدرس تصویر نقشه جزیره (PNG):</label><br>
								<input type="text" name="banners[<?php echo $idx; ?>][map_image]" value="<?php echo esc_url( $map_image ); ?>" class="large-text">
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<button type="button" id="add-banner-btn" class="button" style="margin-bottom:20px;">+ افزودن بنر جدید</button>

			<p class="submit">
				<input type="submit" name="kish_harmony_save_banner" class="button button-primary" value="ذخیره تنظیمات بنرها">
			</p>
		</form>
	</div>

	<script>
	document.addEventListener('DOMContentLoaded', function() {
		const container = document.getElementById('banners-repeater');
		const addBtn = document.getElementById('add-banner-btn');

		if (addBtn && container) {
			addBtn.addEventListener('click', function() {
				const idx = Date.now();
				const html = `
					<div class="banner-item-row" style="background:#fff; border:1px solid #ccc; padding:15px; margin-bottom:15px; border-radius:8px; position:relative;">
						<button type="button" class="button remove-banner-btn" style="position:absolute; top:10px; left:10px; color:red; border-color:red;">حذف این بنر</button>
						<h3 style="margin-top:0;">بنر جدید</h3>
						<div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
							<div>
								<label>عنوان بنر:</label><br>
								<input type="text" name="banners[${idx}][title]" value="" class="large-text">
							</div>
							<div>
								<label>زیرعنوان / توضیحات:</label><br>
								<input type="text" name="banners[${idx}][subtitle]" value="" class="large-text">
							</div>
							<div>
								<label>متن دکمه:</label><br>
								<input type="text" name="banners[${idx}][btn_text]" value="رزرو آنلاین" class="regular-text">
							</div>
							<div>
								<label>لینک دکمه:</label><br>
								<input type="text" name="banners[${idx}][btn_link]" value="#" class="regular-text">
							</div>
							<div>
								<label>آدرس تصویر پس‌زمینه (BG Image):</label><br>
								<input type="text" name="banners[${idx}][bg_image]" value="" class="large-text">
							</div>
							<div>
								<label>آدرس تصویر کاراکتر کوسه (PNG بدون بک‌گراند):</label><br>
								<input type="text" name="banners[${idx}][shark_image]" value="" class="large-text">
							</div>
							<div>
								<label>آدرس تصویر نقشه جزیره (PNG):</label><br>
								<input type="text" name="banners[${idx}][map_image]" value="" class="large-text">
							</div>
						</div>
					</div>
				`;
				container.insertAdjacentHTML('beforeend', html);
			});

			container.addEventListener('click', function(e) {
				if (e.target.classList.contains('remove-banner-btn')) {
					e.target.closest('.banner-item-row').remove();
				}
			});
		}
	});
	</script>
	<?php
}
