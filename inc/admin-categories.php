<?php
/**
 * Categories Settings Page Callback (Dynamic Repeater)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kish_harmony_categories_settings_page() {
	if ( isset( $_POST['kish_harmony_save_categories'] ) && check_admin_referer( 'kish_harmony_categories_nonce' ) ) {
		$title    = sanitize_text_field( $_POST['section_title'] ?? '' );
		$subtitle = sanitize_text_field( $_POST['section_subtitle'] ?? '' );
		$items    = isset( $_POST['categories'] ) ? $_POST['categories'] : array();

		$sanitized_items = array();
		if ( is_array( $items ) ) {
			foreach ( $items as $item ) {
				if ( ! empty( $item['title'] ) ) {
					$sanitized_items[] = array(
						'title'       => sanitize_text_field( $item['title'] ),
						'icon'        => sanitize_text_field( $item['icon'] ?? 'fa-ship' ),
						'image_url'   => esc_url_raw( $item['image_url'] ?? '' ),
						'cat_id'      => intval( $item['cat_id'] ?? 0 ),
						'custom_link' => esc_url_raw( $item['custom_link'] ?? '' ),
					);
				}
			}
		}

		$categories_data = array(
			'section_title'    => $title,
			'section_subtitle' => $subtitle,
			'items'            => $sanitized_items,
		);

		update_option( 'kish_harmony_categories_options', $categories_data );
		echo '<div class="updated"><p>تنظیمات دسته‌بندی تفریحات با موفقیت ذخیره شد.</p></div>';
	}

	$options = get_option( 'kish_harmony_categories_options', array(
		'section_title'    => 'دسته‌بندی‌های تفریحات کیش',
		'section_subtitle' => 'از بین محبوب‌ترین برنامه‌های تفریحی جزیره کیش، انتخاب خود را انجام دهید',
		'items'            => array(
			array( 'title' => 'تور دریایی', 'icon' => 'fa-ship', 'image_url' => '', 'cat_id' => 0, 'custom_link' => '' ),
			array( 'title' => 'پاراسل و شاتل', 'icon' => 'fa-water', 'image_url' => '', 'cat_id' => 0, 'custom_link' => '' ),
			array( 'title' => 'غواصی و تفریحات زیر آب', 'icon' => 'fa-fish', 'image_url' => '', 'cat_id' => 0, 'custom_link' => '' ),
			array( 'title' => 'جنگ‌های شبانه', 'icon' => 'fa-masks-theater', 'image_url' => '', 'cat_id' => 0, 'custom_link' => '' ),
			array( 'title' => 'پارک آبی', 'icon' => 'fa-umbrella-beach', 'image_url' => '', 'cat_id' => 0, 'custom_link' => '' ),
			array( 'title' => 'سافاری و کویر', 'icon' => 'fa-car-side', 'image_url' => '', 'cat_id' => 0, 'custom_link' => '' ),
		),
	) );

	// Fetch WooCommerce Product Categories if active
	$wc_cats = array();
	if ( taxonomy_exists( 'product_cat' ) ) {
		$wc_cats = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false ) );
	}
	?>
	<div class="wrap">
		<h1>تنظیمات دسته‌بندی تفریحات کیش</h1>
		<form method="post" action="">
			<?php wp_nonce_field( 'kish_harmony_categories_nonce' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row">عنوان بخش:</th>
					<td>
						<input type="text" name="section_title" value="<?php echo esc_attr( $options['section_title'] ); ?>" class="large-text">
					</td>
				</tr>
				<tr>
					<th scope="row">زیرعنوان بخش:</th>
					<td>
						<input type="text" name="section_subtitle" value="<?php echo esc_attr( $options['section_subtitle'] ); ?>" class="large-text">
					</td>
				</tr>
			</table>

			<h2>مدیریت آیتم‌های دسته‌بندی</h2>
			<div id="categories-repeater">
				<?php foreach ( $options['items'] as $idx => $item ) : ?>
					<div class="cat-item-row" style="background:#fff; border:1px solid #ccc; padding:15px; margin-bottom:15px; border-radius:8px; position:relative;">
						<button type="button" class="button remove-cat-btn" style="position:absolute; top:10px; left:10px; color:red; border-color:red;">حذف این دسته‌بندی</button>
						<h3 style="margin-top:0;">دسته‌بندی #<?php echo $idx + 1; ?></h3>
						<div style="display:flex; gap:15px; flex-wrap:wrap; align-items:center;">
							<div>
								<label>عنوان دسته‌بندی:</label><br>
								<input type="text" name="categories[<?php echo $idx; ?>][title]" value="<?php echo esc_attr( $item['title'] ); ?>" required>
							</div>
							<div>
								<label>آیکون فونت‌آسام (مثل fa-ship):</label><br>
								<input type="text" name="categories[<?php echo $idx; ?>][icon]" value="<?php echo esc_attr( $item['icon'] ); ?>">
							</div>
							<div>
								<label>تصویر اختصاصی (عکس):</label><br>
								<input type="text" name="categories[<?php echo $idx; ?>][image_url]" value="<?php echo esc_url( $item['image_url'] ); ?>">
							</div>
							<div>
								<label>اتصال به دسته‌بندی ووکامرس:</label><br>
								<select name="categories[<?php echo $idx; ?>][cat_id]">
									<option value="0">-- انتخاب دسته‌بندی ووکامرس --</option>
									<?php if ( ! empty( $wc_cats ) && ! is_wp_error( $wc_cats ) ) : ?>
										<?php foreach ( $wc_cats as $cat ) : ?>
											<option value="<?php echo esc_attr( $cat->term_id ); ?>" <?php selected( $item['cat_id'], $cat->term_id ); ?>><?php echo esc_html( $cat->name ); ?></option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</div>
							<div>
								<label>لینک سفارشی (در صورت عدم انتخاب دسته ووکامرس):</label><br>
								<input type="text" name="categories[<?php echo $idx; ?>][custom_link]" value="<?php echo esc_url( $item['custom_link'] ); ?>">
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<button type="button" id="add-cat-btn" class="button" style="margin-bottom:20px;">+ افزودن دسته‌بندی جدید</button>

			<p class="submit">
				<input type="submit" name="kish_harmony_save_categories" class="button button-primary" value="ذخیره دسته‌بندی‌های تفریحات">
			</p>
		</form>
	</div>

	<script>
	document.addEventListener('DOMContentLoaded', function() {
		const container = document.getElementById('categories-repeater');
		const addBtn = document.getElementById('add-cat-btn');

		if (addBtn && container) {
			addBtn.addEventListener('click', function() {
				const idx = Date.now();
				const html = `
					<div class="cat-item-row" style="background:#fff; border:1px solid #ccc; padding:15px; margin-bottom:15px; border-radius:8px; position:relative;">
						<button type="button" class="button remove-cat-btn" style="position:absolute; top:10px; left:10px; color:red; border-color:red;">حذف این دسته‌بندی</button>
						<h3 style="margin-top:0;">دسته‌بندی جدید</h3>
						<div style="display:flex; gap:15px; flex-wrap:wrap; align-items:center;">
							<div>
								<label>عنوان دسته‌بندی:</label><br>
								<input type="text" name="categories[${idx}][title]" value="" required>
							</div>
							<div>
								<label>آیکون فونت‌آسام (مثل fa-ship):</label><br>
								<input type="text" name="categories[${idx}][icon]" value="fa-ship">
							</div>
							<div>
								<label>تصویر اختصاصی (عکس):</label><br>
								<input type="text" name="categories[${idx}][image_url]" value="">
							</div>
							<div>
								<label>اتصال به دسته‌بندی ووکامرس:</label><br>
								<select name="categories[${idx}][cat_id]">
									<option value="0">-- انتخاب دسته‌بندی ووکامرس --</option>
									<?php if ( ! empty( $wc_cats ) && ! is_wp_error( $wc_cats ) ) : ?>
										<?php foreach ( $wc_cats as $cat ) : ?>
											<option value="<?php echo esc_attr( $cat->term_id ); ?>"><?php echo esc_html( $cat->name ); ?></option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</div>
							<div>
								<label>لینک سفارشی:</label><br>
								<input type="text" name="categories[${idx}][custom_link]" value="#">
							</div>
						</div>
					</div>
				`;
				container.insertAdjacentHTML('beforeend', html);
			});

			container.addEventListener('click', function(e) {
				if (e.target.classList.contains('remove-cat-btn')) {
					e.target.closest('.cat-item-row').remove();
				}
			});
		}
	});
	</script>
	<?php
}
