<?php
/**
 * WooCommerce Custom Meta Boxes & Admin Settings for Special Offers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add Custom Fields to Product Edit Screen
 */
function kish_harmony_add_product_meta_fields() {
	add_meta_box(
		'kish_harmony_product_options',
		'تنظیمات کیش هارمونی (پیشنهاد ویژه)',
		'kish_harmony_render_product_meta_box',
		'product',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'kish_harmony_add_product_meta_fields' );

function kish_harmony_render_product_meta_box( $post ) {
	wp_nonce_field( 'kish_harmony_product_meta_nonce', 'kish_harmony_product_nonce' );

	$is_special = get_post_meta( $post->ID, '_is_special_offer', true );
	$discount   = get_post_meta( $post->ID, '_special_discount_percent', true );
	$capacity   = get_post_meta( $post->ID, '_special_capacity', true );
	?>
	<p>
		<label>
			<input type="checkbox" name="is_special_offer" value="1" <?php checked( $is_special, '1' ); ?>>
			<strong>نمایش در پیشنهاد ویژه صفحه اصلی</strong>
		</label>
	</p>
	<p>
		<label>درصد تخفیف سفارشی (٪):</label><br>
		<input type="number" name="special_discount_percent" value="<?php echo esc_attr( $discount ); ?>" style="width:100%;" min="0" max="100">
		<span class="description" style="font-size:11px;">در صورت خالی بودن، پنهان می‌شود.</span>
	</p>
	<p>
		<label>تعداد ظرفیت باقیمانده:</label><br>
		<input type="number" name="special_capacity" value="<?php echo esc_attr( $capacity ); ?>" style="width:100%;" min="0">
		<span class="description" style="font-size:11px;">در صورت خالی بودن، پنهان می‌شود.</span>
	</p>
	<?php
}

function kish_harmony_save_product_meta( $post_id ) {
	if ( ! isset( $_POST['kish_harmony_product_nonce'] ) || ! wp_verify_nonce( $_POST['kish_harmony_product_nonce'], 'kish_harmony_product_meta_nonce' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$is_special = isset( $_POST['is_special_offer'] ) ? '1' : '0';
	$discount   = sanitize_text_field( $_POST['special_discount_percent'] ?? '' );
	$capacity   = sanitize_text_field( $_POST['special_capacity'] ?? '' );

	update_post_meta( $post_id, '_is_special_offer', $is_special );
	update_post_meta( $post_id, '_special_discount_percent', $discount );
	update_post_meta( $post_id, '_special_capacity', $capacity );
}
add_action( 'save_post_product', 'kish_harmony_save_product_meta' );

/**
 * Admin Settings Page Callback for Special Offers Section Header
 */
function kish_harmony_special_offers_settings_page() {
	if ( isset( $_POST['kish_harmony_save_special_offers'] ) && check_admin_referer( 'kish_harmony_special_offers_nonce' ) ) {
		$title    = sanitize_text_field( $_POST['title'] ?? '' );
		$subtitle = sanitize_text_field( $_POST['subtitle'] ?? '' );

		update_option( 'kish_harmony_special_offers_options', array(
			'title'    => $title,
			'subtitle' => $subtitle,
		) );
		echo '<div class="updated"><p>تنظیمات سرتیتر پیشنهادهای ویژه با موفقیت ذخیره شد.</p></div>';
	}

	$options = get_option( 'kish_harmony_special_offers_options', array(
		'title'    => 'پیشنهادهای ویژه',
		'subtitle' => 'تخفیف‌های استثنایی و محدود تورها و تفریحات کیش',
	) );
	?>
	<div class="wrap">
		<h1>تنظیمات بخش پیشنهادهای ویژه صفحه اصلی</h1>
		<form method="post" action="">
			<?php wp_nonce_field( 'kish_harmony_special_offers_nonce' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row">عنوان سرتیتر:</th>
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
			<p class="submit">
				<input type="submit" name="kish_harmony_save_special_offers" class="button button-primary" value="ذخیره تنظیمات سرتیتر">
			</p>
		</form>
	</div>
	<?php
}
