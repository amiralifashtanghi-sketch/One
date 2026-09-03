<?php
/**
 * WooCommerce On-Sale Products Component
 *
 * @package EAFD_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

// Query on sale products
$args = array(
	'post_type'      => 'product',
	'posts_per_page' => 8,
	'meta_query'     => WC()->query->get_meta_query(),
	'post__in'       => array_merge( array( 0 ), wc_get_product_ids_on_sale() ),
);

$sale_products = new WP_Query( $args );
?>

<section class="eafd-section eafd-sale-section">
	<div class="eafd-section-card">
		<div class="eafd-section-header">
			<h2 class="eafd-section-title">
				<span class="eafd-title-accent"></span>
				تخفیف ها
			</h2>
		</div>

		<div class="eafd-products-row">
			<?php if ( $sale_products->have_posts() ) : ?>
				<?php while ( $sale_products->have_posts() ) : $sale_products->the_post();
					global $product;
					if ( ! $product ) continue;

					// Calculate discount percentage
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
			<?php else : ?>
				<p class="eafd-no-products">در حال حاضر محصول تخفیف‌داری ثبت نشده است.</p>
			<?php endif; ?>
		</div>
	</div>
</section>
