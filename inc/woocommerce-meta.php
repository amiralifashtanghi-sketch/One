<?php
/**
 * WooCommerce Custom Meta Boxes & Auto-Decrement Capacity Logic
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

	$is_special    = get_post_meta( $post->ID, '_is_special_offer', true );
	$discount      = get_post_meta( $post->ID, '_special_discount_percent', true );
	$capacity      = get_post_meta( $post->ID, '_special_capacity', true );
	$is_recreation = get_post_meta( $post->ID, '_is_recreation', true );
	$terms_rules   = get_post_meta( $post->ID, '_recreation_terms', true );
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
		<span class="description" style="font-size:11px;">با ثبت هر سفارش، به‌صورت اتوماتیک کم می‌شود.</span>
	</p>
	<p style="border-top:1px solid #ccc; padding-top:10px; margin-top:10px;">
		<label>
			<input type="checkbox" name="is_recreation" value="1" <?php checked( $is_recreation, '1' ); ?>>
			<strong>این محصول یک تفریح است (نیاز به انتخاب تاریخ)</strong>
		</label>
	</p>
	<p>
		<label>قوانین و مقررات اختصاصی تفریح:</label><br>
		<textarea name="recreation_terms" rows="4" style="width:100%; font-size:12px;" placeholder="قوانین تفریح را اینجا وارد کنید..."><?php echo esc_textarea( $terms_rules ); ?></textarea>
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

	$is_special    = isset( $_POST['is_special_offer'] ) ? '1' : '0';
	$discount      = sanitize_text_field( $_POST['special_discount_percent'] ?? '' );
	$capacity      = sanitize_text_field( $_POST['special_capacity'] ?? '' );
	$is_recreation = isset( $_POST['is_recreation'] ) ? '1' : '0';
	$terms_rules   = sanitize_textarea_field( $_POST['recreation_terms'] ?? '' );

	update_post_meta( $post_id, '_is_special_offer', $is_special );
	update_post_meta( $post_id, '_special_discount_percent', $discount );
	update_post_meta( $post_id, '_special_capacity', $capacity );
	update_post_meta( $post_id, '_is_recreation', $is_recreation );
	update_post_meta( $post_id, '_recreation_terms', $terms_rules );
}
add_action( 'save_post_product', 'kish_harmony_save_product_meta' );

/**
 * Auto Decrement Special Capacity on WooCommerce Order Creation / Payment
 */
function kish_harmony_decrement_special_capacity( $order_id ) {
	if ( ! $order_id ) {
		return;
	}

	$order = wc_get_order( $order_id );
	if ( ! $order ) {
		return;
	}

	// Avoid duplicate reduction
	if ( get_post_meta( $order_id, '_kish_harmony_capacity_reduced', true ) ) {
		return;
	}

	foreach ( $order->get_items() as $item ) {
		$product_id = $item->get_product_id();
		$qty        = $item->get_quantity();

		$current_capacity = get_post_meta( $product_id, '_special_capacity', true );
		if ( '' !== $current_capacity && is_numeric( $current_capacity ) ) {
			$new_capacity = max( 0, intval( $current_capacity ) - intval( $qty ) );
			update_post_meta( $product_id, '_special_capacity', $new_capacity );
		}
	}

	update_post_meta( $order_id, '_kish_harmony_capacity_reduced', '1' );
}
add_action( 'woocommerce_thankyou', 'kish_harmony_decrement_special_capacity' );
add_action( 'woocommerce_order_status_completed', 'kish_harmony_decrement_special_capacity' );

/**
 * Store Recreation Date in Cart Item Data & Order Line Items
 */
function kish_harmony_add_recreation_date_to_cart_item( $cart_item_data, $product_id, $variation_id ) {
	if ( isset( $_POST['recreation_date'] ) && ! empty( $_POST['recreation_date'] ) ) {
		$cart_item_data['recreation_date'] = sanitize_text_field( $_POST['recreation_date'] );
	}
	return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'kish_harmony_add_recreation_date_to_cart_item', 10, 3 );

function kish_harmony_display_recreation_date_in_cart( $item_data, $cart_item ) {
	if ( isset( $cart_item['recreation_date'] ) ) {
		$item_data[] = array(
			'key'   => 'تاریخ حضور / رزرو',
			'value' => wc_clean( $cart_item['recreation_date'] ),
		);
	}
	return $item_data;
}
add_filter( 'woocommerce_get_item_data', 'kish_harmony_display_recreation_date_in_cart', 10, 2 );

function kish_harmony_add_recreation_date_to_order_items( $item, $cart_item_key, $values, $order ) {
	if ( isset( $values['recreation_date'] ) ) {
		$item->add_meta_data( 'تاریخ حضور / رزرو', $values['recreation_date'] );
	}
	if ( isset( $values['car_id'] ) ) {
		$item->add_meta_data( '_car_id', $values['car_id'] );
		$item->add_meta_data( 'تاریخ شروع رزرو خودرو', $values['booking_start'] );
		$item->add_meta_data( 'تاریخ پایان رزرو خودرو', $values['booking_end'] );
	}
}
add_action( 'woocommerce_checkout_create_order_line_item', 'kish_harmony_add_recreation_date_to_order_items', 10, 4 );

/**
 * Save Booked Dates to Car Rental CPT on Order Completion / Payment
 */
function kish_harmony_save_car_booking_dates( $order_id ) {
	if ( ! $order_id ) {
		return;
	}
	$order = wc_get_order( $order_id );
	if ( ! $order ) {
		return;
	}

	foreach ( $order->get_items() as $item ) {
		$car_id        = $item->get_meta( '_car_id' );
		$booking_start = $item->get_meta( 'تاریخ شروع رزرو خودرو' );
		$booking_end   = $item->get_meta( 'تاریخ پایان رزرو خودرو' );

		if ( $car_id && $booking_start && $booking_end ) {
			$existing = get_post_meta( $car_id, '_car_booked_dates', true ) ?: array();
			$existing[] = array(
				'start' => $booking_start,
				'end'   => $booking_end,
			);
			update_post_meta( $car_id, '_car_booked_dates', $existing );
		}
	}
}
add_action( 'woocommerce_order_status_processing', 'kish_harmony_save_car_booking_dates' );
add_action( 'woocommerce_order_status_completed', 'kish_harmony_save_car_booking_dates' );
