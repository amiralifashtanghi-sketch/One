<?php
/**
 * My Account page
 *
 * @package EAFD_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="eafd-myaccount-wrapper">
	<?php
	/**
	 * My Account navigation
	 */
	do_action( 'woocommerce_account_navigation' );
	?>

	<div class="eafd-account-content-card">
		<?php
			/**
			 * My Account content
			 */
			do_action( 'woocommerce_account_content' );
		?>
	</div>
</div>
