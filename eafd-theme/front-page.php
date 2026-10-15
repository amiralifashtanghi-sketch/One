<?php
/**
 * Front Page Template
 *
 * @package EAFD_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="eafd-container">
	<?php
	// 1. Hero Banner Component
	get_template_part( 'inc/template-parts/hero-banner' );

	// 2. Categories Component
	get_template_part( 'inc/template-parts/categories' );

	// 3. On-Sale Products Component
	get_template_part( 'inc/template-parts/sale-products' );

	// 4. Shop Products Grid Component
	get_template_part( 'inc/template-parts/shop-grid' );

	// 5. Blog Articles Component (Horizontal Scroll)
	get_template_part( 'inc/template-parts/blog-articles' );
	?>
</div>

<?php
get_footer();
