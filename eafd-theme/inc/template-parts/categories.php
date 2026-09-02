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
				<?php foreach ( $categories as $cat ) :
					$thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
					$image_url    = wp_get_attachment_url( $thumbnail_id );
					if ( ! $image_url ) {
						$image_url = wc_placeholder_img_src();
					}
					$cat_link     = get_term_link( $cat );
				?>
					<a href="<?php echo esc_url( $cat_link ); ?>" class="eafd-cat-item">
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
				$sample_cats = array( 'عسل', 'روغن های طبیعی', 'گلاب و عرقیات', 'شربت ها', 'زعفران', 'ضروریات خانه' );
				foreach ( $sample_cats as $sample_cat ) :
				?>
					<div class="eafd-cat-item">
						<div class="eafd-cat-thumb">
							<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="1.5"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
						</div>
						<span class="eafd-cat-name"><?php echo esc_html( $sample_cat ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
