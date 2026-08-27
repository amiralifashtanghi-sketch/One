<?php
/**
 * Custom Post Type: Car Rental (رنت خودرو)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kish_harmony_register_car_rental_cpt() {
	$labels = array(
		'name'               => 'رنت خودروها',
		'singular_name'      => 'خودرو',
		'menu_name'          => 'رنت خودرو',
		'add_new'            => 'افزودن خودرو جدید',
		'add_new_item'       => 'افزودن خودرو جدید',
		'edit_item'          => 'ویرایش خودرو',
		'new_item'           => 'خودرو جدید',
		'all_items'          => 'همه خودروها',
		'view_item'          => 'مشاهده خودرو',
		'search_items'       => 'جستجوی خودرو',
		'not_found'          => 'خودرویی یافت نشد',
		'not_found_in_trash' => 'خودرویی در زباله‌دان یافت نشد',
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'has_archive'        => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'car-rental' ),
		'capability_type'    => 'post',
		'menu_icon'          => 'dashicons-car',
		'supports'           => array( 'title', 'editor', 'thumbnail' ),
	);

	register_post_type( 'car_rental', $args );

	// Register Category Taxonomy for Car Rental
	register_taxonomy(
		'car_category',
		'car_rental',
		array(
			'label'             => 'دسته‌بندی خودروها',
			'rewrite'           => array( 'slug' => 'car-category' ),
			'hierarchical'      => true,
			'show_admin_column' => true,
		)
	);
}
add_action( 'init', 'kish_harmony_register_car_rental_cpt' );

/**
 * Add Meta Boxes for Car Details
 */
function kish_harmony_add_car_meta_boxes() {
	add_meta_box(
		'car_details_meta_box',
		'مشخصات و ویژگی‌های خودرو',
		'kish_harmony_render_car_meta_box',
		'car_rental',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'kish_harmony_add_car_meta_boxes' );

function kish_harmony_render_car_meta_box( $post ) {
	wp_nonce_field( 'kish_harmony_car_meta_nonce', 'car_meta_nonce' );

	$price      = get_post_meta( $post->ID, '_car_price', true );
	$model_year = get_post_meta( $post->ID, '_car_model_year', true );
	$transmission = get_post_meta( $post->ID, '_car_transmission', true );
	$fuel       = get_post_meta( $post->ID, '_car_fuel', true );
	$seats      = get_post_meta( $post->ID, '_car_seats', true );
	$deposit    = get_post_meta( $post->ID, '_car_deposit', true );
	?>
	<table class="form-table">
		<tr>
			<th><label>قیمت اجاره روزانه (تومان):</label></th>
			<td><input type="number" name="car_price" value="<?php echo esc_attr( $price ); ?>" class="regular-text" required></td>
		</tr>
		<tr>
			<th><label>مدل / سال ساخت:</label></th>
			<td><input type="text" name="car_model_year" value="<?php echo esc_attr( $model_year ); ?>" class="regular-text" placeholder="مثال: ۲۰۲۳"></td>
		</tr>
		<tr>
			<th><label>نوع گیربکس:</label></th>
			<td>
				<select name="car_transmission">
					<option value="اتوماتیک" <?php selected( $transmission, 'اتوماتیک' ); ?>>اتوماتیک</option>
					<option value="دستی" <?php selected( $transmission, 'دستی' ); ?>>دستی</option>
				</select>
			</td>
		</tr>
		<tr>
			<th><label>نوع سوخت:</label></th>
			<td><input type="text" name="car_fuel" value="<?php echo esc_attr( $fuel ); ?>" class="regular-text" placeholder="مثال: بنزینی / هیبرید"></td>
		</tr>
		<tr>
			<th><label>تعداد سرنشین (ظرفیت):</label></th>
			<td><input type="number" name="car_seats" value="<?php echo esc_attr( $seats ); ?>" class="small-text"> نفر</td>
		</tr>
		<tr>
			<th><label>مبلغ ودیعه خلافی (تومان):</label></th>
			<td><input type="text" name="car_deposit" value="<?php echo esc_attr( $deposit ); ?>" class="regular-text"></td>
		</tr>
	</table>
	<?php
}

function kish_harmony_save_car_meta( $post_id ) {
	if ( ! isset( $_POST['car_meta_nonce'] ) || ! wp_verify_nonce( $_POST['car_meta_nonce'], 'kish_harmony_car_meta_nonce' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	update_post_meta( $post_id, '_car_price', sanitize_text_field( $_POST['car_price'] ?? '' ) );
	update_post_meta( $post_id, '_car_model_year', sanitize_text_field( $_POST['car_model_year'] ?? '' ) );
	update_post_meta( $post_id, '_car_transmission', sanitize_text_field( $_POST['car_transmission'] ?? '' ) );
	update_post_meta( $post_id, '_car_fuel', sanitize_text_field( $_POST['car_fuel'] ?? '' ) );
	update_post_meta( $post_id, '_car_seats', sanitize_text_field( $_POST['car_seats'] ?? '' ) );
	update_post_meta( $post_id, '_car_deposit', sanitize_text_field( $_POST['car_deposit'] ?? '' ) );
}
add_action( 'save_post_car_rental', 'kish_harmony_save_car_meta' );

/**
 * Auto Create "Car Rental" Page on Theme Activation
 */
function kish_harmony_create_car_rental_page() {
	$page_title   = 'اجاره خودرو';
	$page_check   = get_page_by_title( $page_title );
	$page_template = 'page-car-rental.php';

	if ( ! isset( $page_check->ID ) ) {
		$new_page = array(
			'post_title'    => $page_title,
			'post_content'  => '',
			'post_status'   => 'publish',
			'post_type'     => 'page',
		);
		$page_id = wp_insert_post( $new_page );
		if ( $page_id && ! is_wp_error( $page_id ) ) {
			update_post_meta( $page_id, '_wp_page_template', $page_template );
		}
	}
}
add_action( 'after_switch_theme', 'kish_harmony_create_car_rental_page' );
