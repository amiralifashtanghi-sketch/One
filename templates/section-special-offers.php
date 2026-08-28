<?php
/**
 * Classic Professional Special Offers Template
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

$post_ids = get_transient( 'kish_harmony_special_offers_query' );

if ( false === $post_ids ) {
	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => 8,
		'fields'         => 'ids',
		'no_found_rows'  => true,
		'meta_query'     => array(
			array(
				'key'   => '_is_special_offer',
				'value' => '1',
			),
		),
	);
	$post_ids = get_posts( $args );

	if ( empty( $post_ids ) ) {
		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => 8,
			'fields'         => 'ids',
			'no_found_rows'  => true,
		);
		$post_ids = get_posts( $args );
	}

	set_transient( 'kish_harmony_special_offers_query', $post_ids, 5 * MINUTE_IN_SECONDS );
}

$special_query = new WP_Query( array(
	'post_type'      => 'product',
	'post__in'       => ! empty( $post_ids ) ? $post_ids : array( 0 ),
	'orderby'        => 'post__in',
	'posts_per_page' => 8,
	'no_found_rows'  => true,
) );
?>

<div class="special-offers-wrapper" id="special-offers">
	<div class="container">
		<div class="special-section-header">
			<h2 class="special-title"><span class="fire-emoji">🔥</span> <?php echo esc_html( $title ); ?></h2>
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
						$thumb = 'https://via.placeholder.com/265x175?text=Kish+Harmony';
					}

					$regular_price = '';
					$sale_price    = '';
					if ( function_exists( 'wc_get_product' ) ) {
						$product = wc_get_product( $product_id );
						if ( $product ) {
							$regular_price = $product->get_regular_price();
							$sale_price    = $product->get_price();
						}
					}

					$cap_num = is_numeric( $capacity ) ? intval( $capacity ) : 10;
					$cap_percent = min( 100, max( 5, $cap_num * 10 ) );
					$is_low_stock = $cap_percent <= 15;
				?>
					<div class="offer-card">
						<div class="card-image-wrap">
							<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>">
						</div>
						<div class="card-body">
							<h3 class="offer-product-title"><?php the_title(); ?></h3>

							<div class="price-block">
								<?php if ( ! empty( $regular_price ) && $regular_price > $sale_price ) : ?>
									<span class="old-price"><?php echo number_format( $regular_price ); ?> تومان</span>
								<?php endif; ?>
								<span class="new-price"><?php echo number_format( $sale_price ); ?> تومان</span>
							</div>

							<?php if ( ! empty( $discount ) ) : ?>
								<div class="discount-circle">
									<?php echo esc_html( $discount ); ?>٪
								</div>
							<?php endif; ?>

							<?php if ( '' !== $capacity ) : ?>
								<div class="capacity-row">
									<div class="capacity-header">
										<span class="capacity-text">⏳ فقط <?php echo esc_html( $capacity ); ?> سانس</span>
										<span class="capacity-count"><?php echo $cap_percent; ?>٪</span>
									</div>
									<div class="capacity-bar <?php echo $is_low_stock ? 'low-stock' : ''; ?>">
										<div class="fill" style="width: <?php echo $cap_percent; ?>%;"></div>
									</div>
								</div>
							<?php endif; ?>

							<div class="reserve-btn-wrapper">
								<a href="<?php the_permalink(); ?>" class="reserve-btn">🎟️ رزرو با تخفیف</a>
							</div>
						</div>
					</div>
				<?php endwhile; wp_reset_postdata(); ?>
			<?php else : ?>
				<p>محصولی جهت نمایش یافت نشد.</p>
			<?php endif; ?>
		</div>
	</div>
</div>
