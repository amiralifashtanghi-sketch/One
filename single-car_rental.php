<?php
/**
 * Single Car Rental Page Template with Direct Checkout Connection
 */

$car_id       = get_the_ID();
$daily_price  = get_post_meta( $car_id, '_car_daily_price', true );
$model_year   = get_post_meta( $car_id, '_car_model_year', true );
$capacity     = get_post_meta( $car_id, '_car_capacity', true );
$transmission = get_post_meta( $car_id, '_car_transmission', true );
$features     = get_post_meta( $car_id, '_car_features', true );
$wc_prod_id   = get_post_meta( $car_id, '_car_wc_product_id', true );

// Handle Add to Cart for Car Rental with Date Validation
$booking_error = '';
if ( isset( $_POST['book_car_now'] ) && ! empty( $wc_prod_id ) && function_exists( 'WC' ) ) {
	$start_date = sanitize_text_field( $_POST['start_date'] ?? '' );
	$end_date   = sanitize_text_field( $_POST['end_date'] ?? '' );

	if ( empty( $start_date ) || empty( $end_date ) ) {
		$booking_error = 'لطفاً تاریخ شروع و پایان اجاره خودرو را انتخاب کنید.';
	} elseif ( $start_date > $end_date ) {
		$booking_error = 'تاریخ پایان نمی‌تواند قبل از تاریخ شروع باشد.';
	} else {
		// Check for existing bookings overlap stored in post_meta
		$existing_bookings = get_post_meta( $car_id, '_car_booked_dates', true ) ?: array();
		$is_overlapping   = false;

		foreach ( $existing_bookings as $booking ) {
			if ( $start_date <= $booking['end'] && $end_date >= $booking['start'] ) {
				$is_overlapping = true;
				break;
			}
		}

		if ( $is_overlapping ) {
			$booking_error = 'متأسفانه این خودرو در تاریخ انتخابی شما قبلاً رزرو شده است. لطفاً تاریخ دیگری انتخاب نمایید.';
		} else {
			$diff_days = max( 1, ceil( ( strtotime( $end_date ) - strtotime( $start_date ) ) / 86400 ) + 1 );
			WC()->cart->empty_cart();
			WC()->cart->add_to_cart( $wc_prod_id, $diff_days, 0, array(), array(
				'booking_start' => $start_date,
				'booking_end'   => $end_date,
				'car_id'        => $car_id,
			) );
			wp_redirect( wc_get_checkout_url() );
			exit;
		}
	}
}

get_header();
?>

<main id="primary" class="site-main car-single-wrapper">
	<div class="container" style="max-width: 1100px; margin: 40px auto; padding: 0 20px;">
		<div style="background: #fff; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); display: flex; flex-wrap: wrap; gap: 30px;">
			<div style="flex: 1 1 450px;">
				<?php if ( has_post_thumbnail() ) : ?>
					<img src="<?php the_post_thumbnail_url( 'large' ); ?>" alt="<?php the_title_attribute(); ?>" style="width: 100%; border-radius: 16px; object-fit: cover;">
				<?php else : ?>
					<img src="https://via.placeholder.com/600x400?text=Kish+Car+Rental" alt="Car Image" style="width: 100%; border-radius: 16px;">
				<?php endif; ?>
			</div>

			<div style="flex: 1 1 450px; display: flex; flex-direction: column; justify-content: space-between;">
				<div>
					<h1 style="font-size: 2rem; color: var(--kh-primary-blue, #0B63D8); font-weight: 800; margin-top: 0;"><?php the_title(); ?></h1>

					<div style="background: #f8fafc; padding: 15px; border-radius: 12px; margin: 20px 0; display: flex; gap: 20px; flex-wrap: wrap;">
						<?php if ( $model_year ) : ?><div><strong>سال ساخت:</strong> <?php echo esc_html( $model_year ); ?></div><?php endif; ?>
						<?php if ( $capacity ) : ?><div><strong>ظرفیت:</strong> <?php echo esc_html( $capacity ); ?> نفر</div><?php endif; ?>
						<?php if ( $transmission ) : ?><div><strong>گیربکس:</strong> <?php echo esc_html( $transmission ); ?></div><?php endif; ?>
					</div>

					<div class="car-features" style="margin-bottom: 25px;">
						<h3>امکانات خودرو:</h3>
						<p><?php echo esc_html( $features ?: 'دارای بیمه کامل، تحویل در فرودگاه یا محل اقامت شما در کیش' ); ?></p>
					</div>

					<div style="font-size: 1.6rem; color: var(--kh-orange, #FF8A00); font-weight: 800; margin-bottom: 25px;">
						قیمت اجاره روزانه: <?php echo number_format( floatval( $daily_price ) ); ?> تومان
					</div>
				</div>

				<?php if ( ! empty( $booking_error ) ) : ?>
					<div style="background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px; border-radius: 10px; margin-bottom: 15px; font-weight: bold;">
						⚠️ <?php echo esc_html( $booking_error ); ?>
					</div>
				<?php endif; ?>

				<form method="post" style="background: #eef2ff; padding: 20px; border-radius: 14px;">
					<h3 style="margin-top:0; font-size:1.1rem; color:#1e293b;">📅 تقویم رزرو و تحویل خودرو</h3>
					<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
						<div>
							<label style="font-weight: bold; display: block; margin-bottom: 6px;">از تاریخ (تحویل):</label>
							<input type="date" name="start_date" required min="<?php echo date( 'Y-m-d' ); ?>" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
						</div>
						<div>
							<label style="font-weight: bold; display: block; margin-bottom: 6px;">تا تاریخ (عودت):</label>
							<input type="date" name="end_date" required min="<?php echo date( 'Y-m-d' ); ?>" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
						</div>
					</div>

					<button type="submit" name="book_car_now" value="1" style="width: 100%; background: linear-gradient(135deg, var(--kh-orange, #FF8A00) 0%, #e67e00 100%); color: #fff; border: none; padding: 15px; border-radius: 12px; font-size: 1.2rem; font-weight: bold; cursor: pointer; transition: 0.3s;">
						⚡ رزرو آنلاین و پرداخت با درگاه بانک
					</button>
				</form>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
