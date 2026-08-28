<?php
/**
 * Single Product Template for WooCommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main single-product-wrapper">
	<div class="container" style="max-width: 1150px; margin: 40px auto; padding: 0 20px;">
		<?php
		while ( have_posts() ) :
			the_post();
			global $product;
			$product_id = get_the_ID();
			$discount   = get_post_meta( $product_id, '_special_discount_percent', true );
			$capacity   = get_post_meta( $product_id, '_special_capacity', true );
		?>
			<div style="background: #fff; border-radius: 20px; padding: 35px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); display: flex; flex-wrap: wrap; gap: 40px;">
				<div style="flex: 1 1 450px; position: relative;">
					<?php if ( has_post_thumbnail() ) : ?>
						<img src="<?php the_post_thumbnail_url( 'large' ); ?>" alt="<?php the_title_attribute(); ?>" style="width: 100%; border-radius: 16px; object-fit: cover;">
					<?php else : ?>
						<img src="https://via.placeholder.com/600x400?text=Kish+Product" alt="Product Image" style="width: 100%; border-radius: 16px;">
					<?php endif; ?>

					<?php if ( ! empty( $discount ) ) : ?>
						<div style="position: absolute; top: 15px; right: 15px; background: var(--kh-orange, #FF8A00); color: #fff; font-weight: bold; padding: 8px 16px; border-radius: 50px; font-size: 1.1rem;">
							🔥 <?php echo esc_html( $discount ); ?>٪ تخفیف ویژه
						</div>
					<?php endif; ?>
				</div>

				<div style="flex: 1 1 480px; display: flex; flex-direction: column; justify-content: space-between;">
					<div>
						<h1 style="font-size: 2.2rem; color: var(--kh-primary-blue, #0B63D8); font-weight: 800; margin-top: 0;"><?php the_title(); ?></h1>

						<?php if ( ! empty( $capacity ) ) : ?>
							<div style="background: #fff7ed; border: 1px solid #ffedd5; padding: 12px 18px; border-radius: 12px; margin: 15px 0; color: #c2410c; font-weight: bold; display: inline-block;">
								⏳ ظرفیت باقیمانده: <?php echo esc_html( $capacity ); ?> سانس / نفر
							</div>
						<?php endif; ?>

						<div class="product-description" style="margin: 20px 0; line-height: 1.8; color: #475569;">
							<?php the_content(); ?>
						</div>
					</div>

					<div style="background: #f8fafc; padding: 25px; border-radius: 16px; border: 1px solid #e2e8f0;">
						<div style="margin-bottom: 20px;">
							<?php if ( $product ) : ?>
								<div style="font-size: 1.8rem; color: var(--kh-orange, #FF8A00); font-weight: 800;">
									<?php echo $product->get_price_html(); ?>
								</div>
							<?php endif; ?>
						</div>

						<?php woocommerce_template_single_add_to_cart(); ?>
					</div>
				</div>
			</div>
		<?php endwhile; ?>
	</div>
</main>

<?php
get_footer();
