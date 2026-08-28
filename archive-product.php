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
			<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px;">
				<?php
				while ( have_posts() ) :
					the_post();
					global $product;
					$product_id = get_the_ID();
					$thumb      = get_the_post_thumbnail_url( $product_id, 'medium' ) ?: 'https://via.placeholder.com/300x200?text=Kish+Product';
				?>
					<div style="background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); display: flex; flex-direction: column; justify-content: space-between;">
						<div>
							<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>" style="width: 100%; height: 190px; object-fit: cover;">
							<div style="padding: 20px;">
								<h3 style="font-size: 1.2rem; margin: 0 0 10px 0; color: #1e293b; font-weight: 800;"><?php the_title(); ?></h3>
								<div style="font-weight: 800; font-size: 1.2rem; color: var(--kh-orange, #FF8A00);">
									<?php echo $product ? $product->get_price_html() : ''; ?>
								</div>
							</div>
						</div>
						<div style="padding: 0 20px 20px 20px;">
							<a href="<?php the_permalink(); ?>" style="display: block; width: 100%; text-align: center; background: var(--kh-primary-blue, #0B63D8); color: #fff; text-decoration: none; padding: 12px; border-radius: 10px; font-weight: bold;">مشاهده و خرید</a>
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
