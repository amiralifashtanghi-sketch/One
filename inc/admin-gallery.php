<?php
/**
 * Gallery Settings Page Callback (Dynamic Repeater & WordPress Media Uploader)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kish_harmony_gallery_admin_enqueue_scripts( $hook ) {
	if ( false === strpos( $hook, 'kish-harmony-gallery' ) ) {
		return;
	}
	wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'kish_harmony_gallery_admin_enqueue_scripts' );

function kish_harmony_gallery_settings_page() {
	if ( isset( $_POST['kish_harmony_save_gallery'] ) && check_admin_referer( 'kish_harmony_gallery_nonce' ) ) {
		$title      = sanitize_text_field( $_POST['title'] ?? '' );
		$share_text = sanitize_text_field( $_POST['share_text'] ?? '' );
		$images     = isset( $_POST['gallery_images'] ) ? $_POST['gallery_images'] : array();

		$sanitized_images = array();
		if ( is_array( $images ) ) {
			foreach ( $images as $img ) {
				if ( ! empty( $img['url'] ) ) {
					$sanitized_images[] = array(
						'url'     => esc_url_raw( $img['url'] ),
						'caption' => sanitize_text_field( $img['caption'] ?? '' ),
					);
				}
			}
		}

		$gallery_data = array(
			'title'      => $title,
			'share_text' => $share_text,
			'images'     => $sanitized_images,
		);

		update_option( 'kish_harmony_gallery_options', $gallery_data );
		echo '<div class="updated"><p>تنظیمات گالری تصاویر با موفقیت ذخیره شد.</p></div>';
	}

	$options = get_option( 'kish_harmony_gallery_options', array(
		'title'      => 'گالری تصاویر کیش هارمونی',
		'share_text' => '📸 عکس‌هایتان را با ما به اشتراک بگذارین',
		'images'     => array(
			array( 'url' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=800&q=80', 'caption' => 'تفریحات دریایی کیش' ),
			array( 'url' => 'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&w=800&q=80', 'caption' => 'ساحل زیبای مرجان' ),
			array( 'url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80', 'caption' => 'غروب آفتاب کیش' ),
			array( 'url' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=800&q=80', 'caption' => 'غواصی در کیش' ),
			array( 'url' => 'https://images.unsplash.com/photo-1519046904884-53103b34b206?auto=format&fit=crop&w=800&q=80', 'caption' => 'ساحل طلایی کیش' ),
			array( 'url' => 'https://images.unsplash.com/photo-1506929562872-bb421503ef21?auto=format&fit=crop&w=800&q=80', 'caption' => 'قایق‌سواری کیش' ),
		),
	) );
	?>
	<div class="wrap" style="direction: rtl; text-align: right;">
		<h1>مدیریت گالری تصاویر مشتریان</h1>
		<form method="post" action="">
			<?php wp_nonce_field( 'kish_harmony_gallery_nonce' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row">عنوان بخش گالری:</th>
					<td>
						<input type="text" name="title" value="<?php echo esc_attr( $options['title'] ?? 'گالری تصاویر کیش هارمونی' ); ?>" class="large-text">
					</td>
				</tr>
				<tr>
					<th scope="row">متن دعوت به اشتراک‌گذاری:</th>
					<td>
						<input type="text" name="share_text" value="<?php echo esc_attr( $options['share_text'] ?? '📸 عکس‌هایتان را با ما به اشتراک بگذارین' ); ?>" class="large-text">
					</td>
				</tr>
			</table>

			<h2>تصاویر واقعی گالری</h2>
			<div id="gallery-repeater">
				<?php
				$images_list = ! empty( $options['images'] ) && is_array( $options['images'] ) ? $options['images'] : array();
				foreach ( $images_list as $idx => $img ) :
				?>
					<div class="gallery-item-row" style="background:#fff; border:1px solid #ccc; padding:15px; margin-bottom:15px; border-radius:8px; position:relative; display:flex; gap:15px; align-items:center;">
						<button type="button" class="button remove-gallery-btn" style="position:absolute; top:10px; left:10px; color:red; border-color:red;">حذف این عکس</button>
						<div style="flex: 1;">
							<label>آدرس یا آپلود عکس:</label><br>
							<div style="display:flex; gap:8px; margin-top:4px;">
								<input type="text" name="gallery_images[<?php echo $idx; ?>][url]" value="<?php echo esc_url( $img['url'] ); ?>" class="regular-text img-url-input" required>
								<button type="button" class="button upload-media-btn">انتخاب / آپلود عکس</button>
							</div>
						</div>
						<div style="flex: 1;">
							<label>توضیح/کپشن تصویر (Alt):</label><br>
							<input type="text" name="gallery_images[<?php echo $idx; ?>][caption]" value="<?php echo esc_attr( $img['caption'] ?? '' ); ?>" class="regular-text" style="margin-top:4px;">
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<button type="button" id="add-gallery-btn" class="button button-secondary" style="margin-bottom:20px;">+ افزودن تصویر جدید</button>

			<p class="submit">
				<input type="submit" name="kish_harmony_save_gallery" class="button button-primary" value="ذخیره گالری تصاویر">
			</p>
		</form>
	</div>

	<script>
	document.addEventListener('DOMContentLoaded', function() {
		const container = document.getElementById('gallery-repeater');
		const addBtn = document.getElementById('add-gallery-btn');

		if (addBtn && container) {
			addBtn.addEventListener('click', function() {
				const idx = Date.now();
				const html = `
					<div class="gallery-item-row" style="background:#fff; border:1px solid #ccc; padding:15px; margin-bottom:15px; border-radius:8px; position:relative; display:flex; gap:15px; align-items:center;">
						<button type="button" class="button remove-gallery-btn" style="position:absolute; top:10px; left:10px; color:red; border-color:red;">حذف این عکس</button>
						<div style="flex: 1;">
							<label>آدرس یا آپلود عکس:</label><br>
							<div style="display:flex; gap:8px; margin-top:4px;">
								<input type="text" name="gallery_images[${idx}][url]" value="" class="regular-text img-url-input" required>
								<button type="button" class="button upload-media-btn">انتخاب / آپلود عکس</button>
							</div>
						</div>
						<div style="flex: 1;">
							<label>توضیح/کپشن تصویر (Alt):</label><br>
							<input type="text" name="gallery_images[${idx}][caption]" value="" class="regular-text" style="margin-top:4px;">
						</div>
					</div>
				`;
				container.insertAdjacentHTML('beforeend', html);
			});

			container.addEventListener('click', function(e) {
				if (e.target.classList.contains('remove-gallery-btn')) {
					e.target.closest('.gallery-item-row').remove();
				}

				if (e.target.classList.contains('upload-media-btn')) {
					e.preventDefault();
					const button = e.target;
					const inputField = button.parentElement.querySelector('.img-url-input');

					const customUploader = wp.media({
						title: 'انتخاب یا آپلود تصویر گالری',
						button: { text: 'استفاده از این تصویر' },
						multiple: false
					});

					customUploader.on('select', function() {
						const attachment = customUploader.state().get('selection').first().toJSON();
						if (inputField) {
							inputField.value = attachment.url;
						}
					});

					customUploader.open();
				}
			});
		}
	});
	</script>
	<?php
}
