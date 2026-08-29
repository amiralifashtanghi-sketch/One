<?php
/**
 * Template Name: Car Rental Custom Page
 */

get_header();
?>

<main id="primary" class="site-main page-cars-wrapper">
	<div class="container" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
		<div style="text-align: center; margin-bottom: 40px;">
			<h1 style="font-size: 2.2rem; color: var(--kh-primary-blue, #0B63D8); font-weight: 800;">🚗 سامانه آنلاین رنت خودرو کیش</h1>
			<p style="color: #64748b; font-size: 1.1rem;">انتخاب، رزرو فوری و تحویل خودرو در فرودگاه کیش</p>
		</div>

		<?php
		$cars_query = new WP_Query( array(
			'post_type'      => 'car_rental',
			'posts_per_page' => 12,
		) );

		if ( $cars_query->have_posts() ) :
		?>
			<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px;">
				<?php
				while ( $cars_query->have_posts() ) :
					$cars_query->the_post();
					$car_id       = get_the_ID();
					$daily_price  = get_post_meta( $car_id, '_car_price', true ) ?: get_post_meta( $car_id, '_car_daily_price', true );
					$model_year   = get_post_meta( $car_id, '_car_model_year', true );
					$transmission = get_post_meta( $car_id, '_car_transmission', true );
					$thumb        = get_the_post_thumbnail_url( $car_id, 'medium_large' ) ?: 'https://via.placeholder.com/400x250?text=Kish+Car';
				?>
					<div style="background: #fff; border-radius: 18px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.06); display: flex; flex-direction: column; justify-content: space-between; border-top: 4px solid var(--kh-secondary-color, #18D6D8);">
						<div>
							<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>" style="width: 100%; height: 210px; object-fit: cover;">
							<div style="padding: 22px;">
								<h3 style="font-size: 1.35rem; margin: 0 0 10px 0; color: #1e293b; font-weight: 800;"><?php the_title(); ?></h3>
								<div style="color: #64748b; font-size: 0.95rem; margin-bottom: 15px; background: #f8fafc; padding: 10px; border-radius: 8px;">
									<span>مدل: <strong><?php echo esc_html( $model_year ?: '۲۰۲۳' ); ?></strong></span> |
									<span>گیربکس: <strong><?php echo esc_html( $transmission ?: 'اتوماتیک' ); ?></strong></span>
								</div>
								<div style="font-weight: 800; font-size: 1.3rem; color: var(--kh-orange, #FF8A00);">
									<?php echo number_format( floatval( $daily_price ) ); ?> تومان / روز
								</div>
							</div>
						</div>
						<div style="padding: 0 22px 22px 22px;">
							<a href="<?php the_permalink(); ?>" style="display: block; width: 100%; text-align: center; background: linear-gradient(135deg, var(--kh-primary-blue, #0B63D8) 0%, var(--kh-turquoise, #18D6D8) 100%); color: #fff; text-decoration: none; padding: 14px; border-radius: 12px; font-weight: bold; font-size: 1.05rem;">⚡ رزرو آنلاین خودرو</a>
						</div>
					</div>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		<?php else : ?>
			<p style="text-align: center;">هیچ خودرویی تاکنون ثبت نشده است.</p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
