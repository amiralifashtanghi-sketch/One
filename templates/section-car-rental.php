<?php
/**
 * Car Rental Homepage Section Template (Horizontal Scrollable Layout)
 */
?>

<div class="car-rental-section-wrapper">
	<div class="container">
		<div class="section-title-box">
			<h2><i class="fa-solid fa-car-side"></i> اجاره آنلاین خودرو در کیش</h2>
			<p>تجربه‌ای لذت‌بخش و خاطره‌انگیز با رنت لوکس‌ترین ماشین‌های روز در کیش هارمونی</p>
		</div>

		<div class="horizontal-scroll-container">
			<?php
			$args = array(
				'post_type'      => 'car_rental',
				'posts_per_page' => 8,
			);
			$car_query = new WP_Query( $args );

			if ( $car_query->have_posts() ) :
				while ( $car_query->have_posts() ) : $car_query->the_post();
					$car_id       = get_the_ID();
					$price        = get_post_meta( $car_id, '_car_price', true );
					$model_year   = get_post_meta( $car_id, '_car_model_year', true );
					$transmission = get_post_meta( $car_id, '_car_transmission', true );
					$seats        = get_post_meta( $car_id, '_car_seats', true );
					$thumb        = get_the_post_thumbnail_url( $car_id, 'medium_large' );
					if ( ! $thumb ) {
						$thumb = 'https://via.placeholder.com/400x250?text=Kish+Car+Rental';
					}
			?>
					<div class="car-card horizontal-card">
						<div class="car-card-img-wrapper">
							<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>">
							<?php if ( ! empty( $model_year ) ) : ?>
								<span class="car-year-badge"><?php echo esc_html( $model_year ); ?></span>
							<?php endif; ?>
						</div>
						<div class="car-card-body">
							<h3 class="car-title"><?php the_title(); ?></h3>

							<div class="car-features-tags">
								<?php if ( ! empty( $transmission ) ) : ?>
									<span class="car-tag"><i class="fa-solid fa-gear"></i> <?php echo esc_html( $transmission ); ?></span>
								<?php endif; ?>

								<?php if ( ! empty( $seats ) ) : ?>
									<span class="car-tag"><i class="fa-solid fa-user-group"></i> <?php echo esc_html( $seats ); ?> نفره</span>
								<?php endif; ?>
							</div>

							<div class="car-card-footer">
								<div class="car-price-box">
									<span class="car-price-amount"><?php echo number_format( intval( $price ) ); ?></span>
									<span class="car-price-unit">تومان / روزانه</span>
								</div>
								<a href="<?php the_permalink(); ?>" class="car-reserve-btn">درخواست رزرو</a>
							</div>
						</div>
					</div>
			<?php
				endwhile;
				wp_reset_postdata();
			endif;
			?>
		</div>
	</div>
</div>
