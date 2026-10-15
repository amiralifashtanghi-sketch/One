<?php
/**
 * My Account page navigation
 *
 * @package EAFD_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="eafd-account-navigation">
	<div class="eafd-account-nav-header">
		<?php
		$current_user = wp_get_current_user();
		echo get_avatar( $current_user->ID, 60, '', '', array( 'class' => 'eafd-account-avatar' ) );
		?>
		<div class="eafd-account-user-info">
			<span class="eafd-account-user-name"><?php echo esc_html( $current_user->display_name ? $current_user->display_name : $current_user->user_login ); ?></span>
			<span class="eafd-account-user-email"><?php echo esc_html( $current_user->user_email ); ?></span>
		</div>
	</div>

	<ul class="eafd-account-nav-list">
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
			<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>">
					<span><?php echo esc_html( $label ); ?></span>
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
