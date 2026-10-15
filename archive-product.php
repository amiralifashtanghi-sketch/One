<?php
/**
 * WooCommerce Product Archive / Category Template
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main product-archive-wrapper">
	<div class="container" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
		<div style="text-align: center; margin-bottom: 40px;">
			<h1 style="font-size: 2.2rem; color: var(--kh-primary-blue, #0B63D8); font-weight: 800;"><?php woocommerce_page_title(); ?></h1>
		</div>

		<?php if ( woocommerce_product_loop() ) : ?>
			<div class="product-responsive-grid archive-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					global $product;
					$product_id = get_the_ID();
					$thumb      = get_the_post_thumbnail_url( $product_id, 'medium' ) ?: 'https://via.placeholder.com/300x200?text=Kish+Product';
					$discount   = get_post_meta( $product_id, '_special_discount_percent', true );
				?>
					<div class="archive-product-card">
						<div class="card-media-wrapper" style="position: relative;">
							<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>" class="product-card-img">
							<?php if ( ! empty( $discount ) ) : ?>
								<span class="product-badge-discount">🔥 <?php echo esc_html( $discount ); ?>٪</span>
							<?php endif; ?>
						</div>
						<div class="card-content-wrapper">
							<div class="product-categories-meta">
								<?php
								$cats = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'names' ) );
								if ( ! empty( $cats ) ) :
									foreach ( array_slice( $cats, 0, 1 ) as $c_name ) :
								?>
									<span class="cat-tag"><?php echo esc_html( $c_name ); ?></span>
								<?php
									endforeach;
								endif;
								?>
							</div>
							<h3 class="product-card-title"><?php the_title(); ?></h3>
							<div class="product-card-price">
								<?php echo $product ? $product->get_price_html() : ''; ?>
							</div>
						</div>
						<div class="card-action-wrapper">
							<a href="<?php the_permalink(); ?>" class="product-card-btn">مشاهده و خرید</a>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
		<?php else : ?>
			<p style="text-align: center;">محصولی در این بخش یافت نشد.</p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
