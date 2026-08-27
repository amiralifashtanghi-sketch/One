<?php
/**
 * Services Settings Page Callback (Dynamic Service Buttons)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kish_harmony_services_settings_page() {
	if ( isset( $_POST['kish_harmony_save_services'] ) && check_admin_referer( 'kish_harmony_services_nonce' ) ) {
		$items = isset( $_POST['services'] ) ? $_POST['services'] : array();
		$sanitized_items = array();

		if ( is_array( $items ) ) {
			foreach ( $items as $item ) {
				if ( ! empty( $item['title'] ) ) {
					$sanitized_items[] = array(
						'title'     => sanitize_text_field( $item['title'] ),
						'emoji'     => sanitize_text_field( $item['emoji'] ?? '' ),
						'image_url' => esc_url_raw( $item['image_url'] ?? '' ),
						'page_id'   => intval( $item['page_id'] ?? 0 ),
						'badge'     => sanitize_text_field( $item['badge'] ?? '' ),
						'is_special'=> isset( $item['is_special'] ) ? '1' : '0',
					);
				}
			}
		}

		update_option( 'kish_harmony_services_options', $sanitized_items );
		echo '<div class="updated"><p>تنظیمات خدمات ویژه با موفقیت ذخیره شد.</p></div>';
	}

	$services = get_option( 'kish_harmony_services_options', array(
		array( 'title' => 'قطار', 'emoji' => '🚆', 'image_url' => '', 'page_id' => 0, 'badge' => '', 'is_special' => '0' ),
		array( 'title' => 'پرواز', 'emoji' => '✈️', 'image_url' => '', 'page_id' => 0, 'badge' => '', 'is_special' => '0' ),
		array( 'title' => 'هتل', 'emoji' => '🏨', 'image_url' => '', 'page_id' => 0, 'badge' => '', 'is_special' => '0' ),
		array( 'title' => 'اتوبوس', 'emoji' => '🚌', 'image_url' => '', 'page_id' => 0, 'badge' => '', 'is_special' => '0' ),
		array( 'title' => 'ویژه', 'emoji' => '⭐', 'image_url' => '', 'page_id' => 0, 'badge' => 'جدید', 'is_special' => '1' ),
		array( 'title' => 'ویلا و اقامتگاه', 'emoji' => '🏡', 'image_url' => '', 'page_id' => 0, 'badge' => '', 'is_special' => '0' ),
		array( 'title' => 'تور', 'emoji' => '🧳', 'image_url' => '', 'page_id' => 0, 'badge' => '', 'is_special' => '0' ),
		array( 'title' => 'نسخه جدید', 'emoji' => '✨', 'image_url' => '', 'page_id' => 0, 'badge' => '', 'is_special' => '1' ),
	) );

	$pages = get_pages();
	?>
	<div class="wrap">
		<h1>تنظیمات خدمات ویژه شما (دکمه‌های بالای سایت)</h1>
		<p>می‌توانید به تعداد دلخواه خدمت اضافه کرده یا آیتم‌های موجود را حذف نمایید.</p>

		<form method="post" action="">
			<?php wp_nonce_field( 'kish_harmony_services_nonce' ); ?>

			<div id="services-repeater">
				<?php foreach ( $services as $idx => $service ) : ?>
					<div class="service-item-row" style="background:#fff; border:1px solid #ccc; padding:15px; margin-bottom:15px; border-radius:8px; position:relative;">
						<button type="button" class="button remove-service-btn" style="position:absolute; top:10px; left:10px; color:red; border-color:red;">حذف</button>
						<h3 style="margin-top:0;">خدمت #<span class="service-idx"><?php echo $idx + 1; ?></span></h3>
						<div style="display:flex; gap:15px; flex-wrap:wrap; align-items:center;">
							<div>
								<label>عنوان خدمت:</label><br>
								<input type="text" name="services[<?php echo $idx; ?>][title]" value="<?php echo esc_attr( $service['title'] ); ?>" required>
							</div>
							<div>
								<label>ایموجی / آیکون متنی:</label><br>
								<input type="text" name="services[<?php echo $idx; ?>][emoji]" value="<?php echo esc_attr( $service['emoji'] ); ?>" style="width:70px;">
							</div>
							<div>
								<label>آدرس تصویر اختصاصی (اختیاری):</label><br>
								<input type="text" name="services[<?php echo $idx; ?>][image_url]" value="<?php echo esc_url( $service['image_url'] ); ?>">
							</div>
							<div>
								<label>لینک به برگه:</label><br>
								<select name="services[<?php echo $idx; ?>][page_id]">
									<option value="0">-- انتخاب برگه --</option>
									<?php foreach ( $pages as $p ) : ?>
										<option value="<?php echo esc_attr( $p->ID ); ?>" <?php selected( $service['page_id'], $p->ID ); ?>><?php echo esc_html( $p->post_title ); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div>
								<label>برچسب/نشان (مثل: جدید):</label><br>
								<input type="text" name="services[<?php echo $idx; ?>][badge]" value="<?php echo esc_attr( $service['badge'] ); ?>" style="width:100px;">
							</div>
							<div>
								<label><br>
									<input type="checkbox" name="services[<?php echo $idx; ?>][is_special]" value="1" <?php checked( $service['is_special'], '1' ); ?>>
									آیتم ویژه (رنگ آبی)
								</label>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<button type="button" id="add-service-btn" class="button" style="margin-bottom:20px;">+ افزودن خدمت جدید</button>

			<p class="submit">
				<input type="submit" name="kish_harmony_save_services" class="button button-primary" value="ذخیره خدمات ویژه">
			</p>
		</form>
	</div>

	<script>
	document.addEventListener('DOMContentLoaded', function() {
		const container = document.getElementById('services-repeater');
		const addBtn = document.getElementById('add-service-btn');

		if (addBtn && container) {
			addBtn.addEventListener('click', function() {
				const idx = Date.now();
				const html = `
					<div class="service-item-row" style="background:#fff; border:1px solid #ccc; padding:15px; margin-bottom:15px; border-radius:8px; position:relative;">
						<button type="button" class="button remove-service-btn" style="position:absolute; top:10px; left:10px; color:red; border-color:red;">حذف</button>
						<h3 style="margin-top:0;">خدمت جدید</h3>
						<div style="display:flex; gap:15px; flex-wrap:wrap; align-items:center;">
							<div>
								<label>عنوان خدمت:</label><br>
								<input type="text" name="services[${idx}][title]" value="" required>
							</div>
							<div>
								<label>ایموجی / آیکون متنی:</label><br>
								<input type="text" name="services[${idx}][emoji]" value="✨" style="width:70px;">
							</div>
							<div>
								<label>آدرس تصویر اختصاصی (اختیاری):</label><br>
								<input type="text" name="services[${idx}][image_url]" value="">
							</div>
							<div>
								<label>لینک به برگه:</label><br>
								<select name="services[${idx}][page_id]">
									<option value="0">-- انتخاب برگه --</option>
									<?php foreach ( $pages as $p ) : ?>
										<option value="<?php echo esc_attr( $p->ID ); ?>"><?php echo esc_html( $p->post_title ); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div>
								<label>برچسب/نشان (مثل: جدید):</label><br>
								<input type="text" name="services[${idx}][badge]" value="" style="width:100px;">
							</div>
							<div>
								<label><br>
									<input type="checkbox" name="services[${idx}][is_special]" value="1">
									آیتم ویژه (رنگ آبی)
								</label>
							</div>
						</div>
					</div>
				`;
				container.insertAdjacentHTML('beforeend', html);
			});

			container.addEventListener('click', function(e) {
				if (e.target.classList.contains('remove-service-btn')) {
					e.target.closest('.service-item-row').remove();
				}
			});
		}
	});
	</script>
	<?php
}
