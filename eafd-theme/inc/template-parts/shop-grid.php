<?php
/**
 * WooCommerce Main Shop Grid Component
 *
 * @package EAFD_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$args = array(
	'post_type'      => 'product',
	'posts_per_page' => 12,
	'paged'          => $paged,
);

$shop_products = new WP_Query( $args );
?>

<section class="eafd-section eafd-shop-section">
	<!-- Search Bar above Shop Products (Plan 1) -->
	<?php get_template_part( 'inc/template-parts/search', 'bar' ); ?>

	<div class="eafd-section-card">
		<div class="eafd-section-header">
			<h2 class="eafd-section-title">
				<span class="eafd-title-accent"></span>
				محصولات فروشگاه
			</h2>
		</div>

		<?php if ( $shop_products->have_posts() ) : ?>
			<div class="eafd-shop-grid">
				<?php while ( $shop_products->have_posts() ) : $shop_products->the_post();
					global $product;
					if ( ! $product ) continue;

					$regular_price = (float) $product->get_regular_price();
					$sale_price    = (float) $product->get_sale_price();
					$discount_pct  = 0;

					if ( $regular_price > 0 && $sale_price > 0 ) {
						$discount_pct = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
					}
				?>
					<div class="eafd-product-card">
						<?php if ( $discount_pct > 0 ) : ?>
							<div class="eafd-discount-badge">%<?php echo esc_html( eafd_convert_to_persian_digits( $discount_pct ) ); ?></div>
						<?php endif; ?>

						<a href="<?php the_permalink(); ?>" class="eafd-product-thumb-link">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'woocommerce_thumbnail', array( 'class' => 'eafd-product-thumb', 'loading' => 'lazy' ) ); ?>
							<?php else : ?>
								<img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="<?php the_title_attribute(); ?>" class="eafd-product-thumb" />
							<?php endif; ?>
						</a>

						<h3 class="eafd-product-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>

						<div class="eafd-product-price-box">
							<?php if ( $regular_price > 0 && $sale_price > 0 ) : ?>
								<del class="eafd-regular-price"><?php echo esc_html( eafd_convert_to_persian_digits( number_format( $regular_price ) ) ); ?> تومان</del>
								<ins class="eafd-sale-price"><?php echo esc_html( eafd_convert_to_persian_digits( number_format( $sale_price ) ) ); ?> تومان</ins>
							<?php else : ?>
								<span class="eafd-sale-price"><?php echo $product->get_price_html(); ?></span>
							<?php endif; ?>
						</div>
					</div>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		<?php else : ?>
			<!-- Sample demo cards matching user screenshot if no products yet -->
			<div class="eafd-shop-grid">
				<?php
				$sample_items = array(
					array( 'title' => 'آبلیموی طبیعی درجه یک یک لیتری', 'price' => '۴۵۰,۰۰۰ تومان' ),
					array( 'title' => 'سرکه انگور خانگی یک لیتری', 'price' => '۱۴۰,۰۰۰ تومان', 'old_price' => '۱۵۰,۰۰۰ تومان', 'discount' => '٪۷' ),
					array( 'title' => 'رب خانگی یک کیلویی', 'price' => '۱۴۵,۰۰۰ تومان', 'old_price' => '۱۵۰,۰۰۰ تومان', 'discount' => '٪۴' ),
					array( 'title' => 'نمک الماس', 'price' => '۱۴۵,۰۰۰ تومان', 'old_price' => '۱۵۰,۰۰۰ تومان', 'discount' => '٪۴' ),
				);
				foreach ( $sample_items as $item ) :
				?>
					<div class="eafd-product-card">
						<?php if ( isset( $item['discount'] ) ) : ?>
							<div class="eafd-discount-badge"><?php echo esc_html( $item['discount'] ); ?></div>
						<?php endif; ?>
						<div class="eafd-product-thumb-placeholder">
							<svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#aaa" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
						</div>
						<h3 class="eafd-product-title"><a href="#"><?php echo esc_html( $item['title'] ); ?></a></h3>
						<div class="eafd-product-price-box">
							<?php if ( isset( $item['old_price'] ) ) : ?>
								<del class="eafd-regular-price"><?php echo esc_html( $item['old_price'] ); ?></del>
							<?php endif; ?>
							<span class="eafd-sale-price"><?php echo esc_html( $item['price'] ); ?></span>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
