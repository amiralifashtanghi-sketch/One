<?php
/**
 * Car Rental Homepage Section Template (Kish Harmony Dynamic Only)
 */

$section_title    = get_option( 'car_rental_title', 'خودروهای ویژه رنت کیش' );
$section_subtitle = get_option( 'car_rental_subtitle', 'سامانه جامع گردشگری و رنت خودرو در جزیره کیش' );
$section_hint     = get_option( 'car_rental_hint', 'برای مشاهده بیشتر بکشید یا کلیک کنید' );
$btn_label        = get_option( 'car_rental_btn_text', 'رزرو آنلاین' );
?>

<div class="car-rental-section-wrapper">
	<div class="container">

		<!-- Brand Header -->
		<div class="brand-header">
			<div class="logo-text-brand">کیش <span>هارمونی</span></div>
			<p class="tagline"><i class="fa-solid fa-umbrella-beach"></i> <?php echo esc_html( $section_subtitle ); ?> <i class="fa-solid fa-umbrella-beach"></i></p>
		</div>

		<!-- Section Head & Controls -->
		<div class="car-section-head">
			<h3><i class="fa-solid fa-th-large"></i> <?php echo esc_html( $section_title ); ?></h3>
		</div>

		<div class="scroll-controls">
			<button class="scroll-btn" id="scrollRight" aria-label="بعدی"><i class="fa-solid fa-chevron-right"></i></button>
			<span class="scroll-hint"><i class="fa-solid fa-arrows-alt-h"></i> <?php echo esc_html( $section_hint ); ?></span>
			<button class="scroll-btn" id="scrollLeft" aria-label="قبلی"><i class="fa-solid fa-chevron-left"></i></button>
		</div>

		<!-- Scroll Container -->
		<div class="scroll-container" id="scroller">
			<?php
			$cars = get_transient( 'kish_harmony_car_rentals_query' );

			if ( false === $cars ) {
				$args = array(
					'post_type'      => 'car_rental',
					'posts_per_page' => 12,
					'no_found_rows'  => true,
				);
				$car_query = new WP_Query( $args );

				$cars = array();

				if ( $car_query->have_posts() ) {
					while ( $car_query->have_posts() ) {
						$car_query->the_post();
						$car_id       = get_the_ID();
						$price        = get_post_meta( $car_id, '_car_price', true );
						$model_year   = get_post_meta( $car_id, '_car_model_year', true );
						$transmission = get_post_meta( $car_id, '_car_transmission', true );
						$fuel         = get_post_meta( $car_id, '_car_fuel', true );
						$seats        = get_post_meta( $car_id, '_car_seats', true );
						$deposit      = get_post_meta( $car_id, '_car_deposit', true );
						$featured     = get_post_meta( $car_id, '_car_featured', true );
						$thumb        = get_the_post_thumbnail_url( $car_id, 'medium_large' );

						$cars[] = array(
							'title'        => get_the_title(),
							'link'         => get_permalink(),
							'price'        => ! empty( $price ) ? intval( $price ) : 0,
							'year'         => $model_year,
							'transmission' => $transmission,
							'fuel'         => $fuel,
							'seats'        => $seats,
							'deposit'      => $deposit,
							'featured'     => ( $featured === '1' || $featured === 'yes' ),
							'thumb'        => $thumb,
						);
					}
					wp_reset_postdata();
				}
				set_transient( 'kish_harmony_car_rentals_query', $cars, 10 * MINUTE_IN_SECONDS );
			}


			if ( ! empty( $cars ) ) :
				// Chunk into groups of 3 cards each
				$car_groups = array_chunk( $cars, 3 );
				$grad_index = 1;

				foreach ( $car_groups as $group_idx => $group ) :
				?>
					<div class="card-group">
						<?php foreach ( $group as $car ) :
							$is_feat    = ! empty( $car['featured'] );
							$grad_class = 'grad' . ( ( $grad_index % 5 ) + 1 );
							$grad_index++;
						?>
							<div class="car-card <?php echo $is_feat ? 'featured' : ''; ?>">
								<div class="car-img <?php echo esc_attr( $grad_class ); ?>">
									<?php if ( ! empty( $car['thumb'] ) ) : ?>
										<img src="<?php echo esc_url( $car['thumb'] ); ?>" alt="<?php echo esc_attr( $car['title'] ); ?>">
									<?php else : ?>
										<i class="fa-solid fa-car-side"></i>
									<?php endif; ?>

									<?php if ( $is_feat ) : ?>
										<span class="badge-special">ویژه</span>
									<?php endif; ?>
								</div>

								<div class="car-info">
									<div class="car-name"><?php echo esc_html( $car['title'] ); ?></div>
									<div class="car-meta">
										<?php if ( ! empty( $car['transmission'] ) ) : ?>
											<span><i class="fa-solid fa-cog"></i> <?php echo esc_html( $car['transmission'] ); ?></span>
										<?php endif; ?>
										<?php if ( ! empty( $car['fuel'] ) ) : ?>
											<span><i class="fa-solid fa-gas-pump"></i> <?php echo esc_html( $car['fuel'] ); ?></span>
										<?php endif; ?>
										<?php if ( ! empty( $car['seats'] ) ) : ?>
											<span><i class="fa-solid fa-users"></i> <?php echo esc_html( $car['seats'] ); ?> نفره</span>
										<?php endif; ?>
									</div>
									<div class="car-rating">
										<i class="fa-solid fa-star"></i> ۵.۰ <span>(تحویل در کیش)</span>
									</div>
								</div>

								<div class="car-action">
									<div class="car-price">
										<?php echo number_format( $car['price'] ); ?>
										<small>هزار تومان / روز</small>
									</div>
									<a href="<?php echo esc_url( $car['link'] ); ?>" class="btn-book"><?php echo esc_html( $btn_label ); ?></a>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php
				endforeach;
			else :
			?>
				<p style="text-align:center; padding:30px; width:100%;">هنوز هیچ خودرویی در پنل ادمین ثبت نشده است.</p>
			<?php endif; ?>
		</div>

	</div>
</div>
