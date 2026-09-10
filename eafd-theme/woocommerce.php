<?php
/**
 * WooCommerce Fallback Template
 *
 * @package EAFD_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="eafd-container">
	<div class="eafd-section-card eafd-wc-wrapper" style="margin-top: 20px;">
		<?php woocommerce_content(); ?>
	</div>
</div>

<?php
get_footer();
