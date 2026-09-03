<?php
/**
 * WooCommerce Categories Component
 *
 * @package EAFD_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$categories = get_terms( array(
	'taxonomy'   => 'product_cat',
	'hide_empty' => false,
	'number'     => 12,
) );

?>

<section class="eafd-section eafd-categories-section">
	<div class="eafd-section-card">
		<div class="eafd-section-header">
			<h2 class="eafd-section-title">
				<span class="eafd-title-accent"></span>
				دسته بندی ها
			</h2>
		</div>

		<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
			<div class="eafd-categories-grid">
				<?php
				$all_products_img = eafd_get_option( 'all_products_cat_img', '' );
				?>
				<!-- All Products item -->
				<a href="#" class="eafd-cat-item eafd-cat-ajax-btn active" data-category-slug="">
					<div class="eafd-cat-thumb">
						<?php if ( ! empty( $all_products_img ) ) : ?>
							<img src="<?php echo esc_url( $all_products_img ); ?>" alt="همه محصولات" width="80" height="80" loading="lazy" />
						<?php else : ?>
							<!-- Modern Shopping Bag Icon 🛍️ -->
							<svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
								<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
								<line x1="3" y1="6" x2="21" y2="6"></line>
								<path d="M16 10a4 4 0 0 1-8 0"></path>
							</svg>
						<?php endif; ?>
					</div>
					<span class="eafd-cat-name">همه محصولات</span>
				</a>

				<?php foreach ( $categories as $cat ) :
					$thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
					$image_url    = wp_get_attachment_url( $thumbnail_id );
					if ( ! $image_url ) {
						$image_url = wc_placeholder_img_src();
					}
				?>
					<a href="#" class="eafd-cat-item eafd-cat-ajax-btn" data-category-slug="<?php echo esc_attr( $cat->slug ); ?>">
						<div class="eafd-cat-thumb">
							<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $cat->name ); ?>" width="80" height="80" loading="lazy" />
						</div>
						<span class="eafd-cat-name"><?php echo esc_html( $cat->name ); ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<!-- Fallback categories if WooCommerce has no categories yet -->
			<div class="eafd-categories-grid">
				<?php
				$all_products_img = eafd_get_option( 'all_products_cat_img', '' );
				?>
				<a href="#" class="eafd-cat-item eafd-cat-ajax-btn active" data-category-slug="">
					<div class="eafd-cat-thumb">
						<?php if ( ! empty( $all_products_img ) ) : ?>
							<img src="<?php echo esc_url( $all_products_img ); ?>" alt="همه محصولات" width="80" height="80" loading="lazy" />
						<?php else : ?>
							<svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
								<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
								<line x1="3" y1="6" x2="21" y2="6"></line>
								<path d="M16 10a4 4 0 0 1-8 0"></path>
							</svg>
						<?php endif; ?>
					</div>
					<span class="eafd-cat-name">همه محصولات</span>
				</a>
				<?php
				$sample_cats = array( 'عسل', 'روغن های طبیعی', 'گلاب و عرقیات', 'شربت ها', 'زعفران', 'ضروریات خانه' );
				foreach ( $sample_cats as $sample_cat ) :
				?>
					<a href="#" class="eafd-cat-item eafd-cat-ajax-btn" data-category-slug="<?php echo esc_attr( sanitize_title( $sample_cat ) ); ?>">
						<div class="eafd-cat-thumb">
							<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="1.5"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
						</div>
						<span class="eafd-cat-name"><?php echo esc_html( $sample_cat ); ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
