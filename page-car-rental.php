<?php
/**
 * Template Name: اجاره خودرو (Car Rental Page)
 */
get_header();
?>

<div class="car-rental-page-wrapper">
	<div class="container">
		<div class="car-page-header">
			<h1><i class="fa-solid fa-car"></i> اجاره آنلاین خودرو در جزیره کیش</h1>
			<p>جدیدترین و لوکس‌ترین خودروهای کیش را با بهترین قیمت و شرایط آسان رزرو کنید</p>
		</div>

		<div class="car-grid">
			<?php
			$args = array(
				'post_type'      => 'car_rental',
				'posts_per_page' => 12,
			);
			$car_query = new WP_Query( $args );

			if ( $car_query->have_posts() ) :
				while ( $car_query->have_posts() ) : $car_query->the_post();
					$car_id       = get_the_ID();
					$price        = get_post_meta( $car_id, '_car_price', true );
					$model_year   = get_post_meta( $car_id, '_car_model_year', true );
					$transmission = get_post_meta( $car_id, '_car_transmission', true );
					$fuel         = get_post_meta( $car_id, '_car_fuel', true );
					$seats        = get_post_meta( $car_id, '_car_seats', true );
					$thumb        = get_the_post_thumbnail_url( $car_id, 'large' );
					if ( ! $thumb ) {
						$thumb = 'https://via.placeholder.com/400x250?text=Kish+Car+Rental';
					}
			?>
					<div class="car-card">
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

								<?php if ( ! empty( $fuel ) ) : ?>
									<span class="car-tag"><i class="fa-solid fa-gas-pump"></i> <?php echo esc_html( $fuel ); ?></span>
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
			else :
				echo '<p>هیچ خودرویی تاکنون ثبت نشده است.</p>';
			endif;
			?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
