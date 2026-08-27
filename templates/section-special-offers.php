<?php
/**
 * Special Offers Section Template
 */
$options  = get_option( 'kish_harmony_special_offers_options', array() );
$title    = ! empty( $options['title'] ) ? $options['title'] : 'پیشنهادهای ویژه';
$subtitle = ! empty( $options['subtitle'] ) ? $options['subtitle'] : 'تخفیف‌های استثنایی و محدود تورها و تفریحات کیش';

$args = array(
	'post_type'      => 'product',
	'posts_per_page' => 8,
	'meta_query'     => array(
		array(
			'key'   => '_is_special_offer',
			'value' => '1',
		),
	),
);

$special_query = new WP_Query( $args );

// Fallback if no specific special offer ticked
if ( ! $special_query->have_posts() ) {
	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => 8,
	);
	$special_query = new WP_Query( $args );
}
?>

<div class="special-offers-wrapper" id="special-offers">
	<div class="container">
		<div class="special-section-header">
			<h2 class="special-title"><span class="fire-icon">🔥</span> <?php echo esc_html( $title ); ?></h2>
			<p class="special-subtitle"><?php echo esc_html( $subtitle ); ?></p>
		</div>

		<div class="special-offers-scroll-container">
			<?php if ( $special_query->have_posts() ) : ?>
				<?php while ( $special_query->have_posts() ) : $special_query->the_post();
					$product_id = get_the_ID();
					$discount   = get_post_meta( $product_id, '_special_discount_percent', true );
					$capacity   = get_post_meta( $product_id, '_special_capacity', true );
					$thumb      = get_the_post_thumbnail_url( $product_id, 'medium' );
					if ( ! $thumb ) {
						$thumb = 'https://via.placeholder.com/300x200?text=Kish+Harmony';
					}

					$price_html = '';
					if ( function_exists( 'wc_get_product' ) ) {
						$product = wc_get_product( $product_id );
						if ( $product ) {
							$price_html = $product->get_price_html();
						}
					}
				?>
					<div class="special-product-card">
						<div class="special-card-image-box">
							<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>">
						</div>
						<div class="special-card-body">
							<h3 class="special-product-name"><?php the_title(); ?></h3>

							<!-- Conditional Discount & Capacity Meta -->
							<?php if ( ! empty( $discount ) || ! empty( $capacity ) ) : ?>
								<div class="special-meta-row">
									<?php if ( ! empty( $discount ) ) : ?>
										<span class="special-discount-tag"><?php echo esc_html( $discount ); ?>٪ تخفیف</span>
									<?php endif; ?>

									<?php if ( ! empty( $capacity ) ) : ?>
										<span class="special-capacity-tag"><i class="fa-solid fa-users"></i> ظرفیت: <?php echo esc_html( $capacity ); ?> نفر</span>
									<?php endif; ?>
								</div>
							<?php endif; ?>

							<div class="special-price-box">
								<?php echo $price_html; ?>
							</div>

							<a href="<?php the_permalink(); ?>" class="special-reserve-btn">رزرو آنلاین <i class="fa-solid fa-angle-left"></i></a>
						</div>
					</div>
				<?php endwhile; wp_reset_postdata(); ?>
			<?php else : ?>
				<p>محصولی برای نمایش وجود ندارد.</p>
			<?php endif; ?>
		</div>
	</div>
</div>
