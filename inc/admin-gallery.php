<?php
/**
 * Gallery Settings Page Callback
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kish_harmony_gallery_settings_page() {
	if ( isset( $_POST['kish_harmony_save_gallery'] ) && check_admin_referer( 'kish_harmony_gallery_nonce' ) ) {
		$title    = sanitize_text_field( $_POST['title'] ?? '' );
		$subtitle = sanitize_text_field( $_POST['subtitle'] ?? '' );
		$images   = isset( $_POST['gallery_images'] ) ? $_POST['gallery_images'] : array();

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
			'title'    => $title,
			'subtitle' => $subtitle,
			'images'   => $sanitized_images,
		);

		update_option( 'kish_harmony_gallery_options', $gallery_data );
		echo '<div class="updated"><p>تنظیمات گالری تصاویر با موفقیت ذخیره شد.</p></div>';
	}

	$options = get_option( 'kish_harmony_gallery_options', array(
		'title'    => 'گالری تصاویر کیش هارمونی',
		'subtitle' => 'تصاویر واقعی ثبت شده توسط مسافران و همراهان عزیز ما در کیش',
		'images'   => array(
			array( 'url' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=800&q=80', 'caption' => 'تفریحات دریایی کیش' ),
			array( 'url' => 'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&w=800&q=80', 'caption' => 'ساحل زیبای مرجان' ),
			array( 'url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80', 'caption' => 'غروب آفتاب کیش' ),
			array( 'url' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=800&q=80', 'caption' => 'غواصی در کیش' ),
		),
	) );
	?>
	<div class="wrap">
		<h1>مدیریت گالری تصاویر مشتریان</h1>
		<form method="post" action="">
			<?php wp_nonce_field( 'kish_harmony_gallery_nonce' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row">عنوان بخش گالری:</th>
					<td>
						<input type="text" name="title" value="<?php echo esc_attr( $options['title'] ); ?>" class="large-text">
					</td>
				</tr>
				<tr>
					<th scope="row">متن زیرعنوان:</th>
					<td>
						<input type="text" name="subtitle" value="<?php echo esc_attr( $options['subtitle'] ); ?>" class="large-text">
					</td>
				</tr>
			</table>

			<h2>تصاویر گالری</h2>
			<div id="gallery-repeater">
				<?php foreach ( $options['images'] as $idx => $img ) : ?>
					<div style="background:#fff; border:1px solid #ccc; padding:15px; margin-bottom:15px; border-radius:8px; display:flex; gap:15px; align-items:center;">
						<div>
							<label>آدرس تصویر (URL):</label><br>
							<input type="text" name="gallery_images[<?php echo $idx; ?>][url]" value="<?php echo esc_url( $img['url'] ); ?>" class="regular-text" required>
						</div>
						<div>
							<label>توضیح/کپشن تصویر:</label><br>
							<input type="text" name="gallery_images[<?php echo $idx; ?>][caption]" value="<?php echo esc_attr( $img['caption'] ); ?>" class="regular-text">
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<p class="submit">
				<input type="submit" name="kish_harmony_save_gallery" class="button button-primary" value="ذخیره گالری تصاویر">
			</p>
		</form>
	</div>
	<?php
}
