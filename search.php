<?php
/**
 * Search Results Template
 */

get_header();
?>

<main id="primary" class="site-main search-results-wrapper">
	<div class="container" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
		<div style="text-align: center; margin-bottom: 40px;">
			<h1 style="font-size: 2rem; color: var(--kh-primary-blue, #0B63D8); font-weight: 800;">
				🔍 نتایج جستجو برای: «<?php echo esc_html( get_search_query() ); ?>»
			</h1>
		</div>

		<?php if ( have_posts() ) : ?>
			<div class="product-responsive-grid archive-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					$product_id = get_the_ID();
					$thumb      = get_the_post_thumbnail_url( $product_id, 'medium' ) ?: 'https://via.placeholder.com/400x250?text=Kish+Harmony';
					$product    = wc_get_product( $product_id );
				?>
					<div class="archive-product-card">
						<div class="card-media-wrapper" style="position: relative;">
							<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>" class="product-card-img">
						</div>
						<div class="card-content-wrapper">
							<div class="product-categories-meta">
								<span class="cat-tag"><?php echo esc_html( get_post_type() ); ?></span>
							</div>
							<h3 class="product-card-title"><?php the_title(); ?></h3>
							<?php if ( $product ) : ?>
								<div class="product-card-price">
									<?php echo $product->get_price_html(); ?>
								</div>
							<?php endif; ?>
						</div>
						<div class="card-action-wrapper">
							<a href="<?php the_permalink(); ?>" class="product-card-btn">مشاهده جزئیات</a>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
			<div style="margin-top: 40px; text-align: center;">
				<?php the_posts_pagination(); ?>
			</div>
		<?php else : ?>
			<p style="text-align: center;">موردی متناسب با جستجوی شما یافت نشد.</p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
