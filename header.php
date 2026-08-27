<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
$header_options = get_option( 'kish_harmony_header_options', array() );
$brand_name     = ! empty( $header_options['brand_name'] ) ? $header_options['brand_name'] : 'کیش هارمونی';
$header_menu_id = ! empty( $header_options['header_menu_id'] ) ? $header_options['header_menu_id'] : 0;
$enable_gtranslate = isset( $header_options['enable_gtranslate'] ) ? $header_options['enable_gtranslate'] : '1';

// WooCommerce Account & Cart Links
$account_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : '#';
$cart_url    = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '#';
?>

<!-- Glassmorphic Header -->
<header class="header" id="header">
	<div class="header__left">
		<button class="hamburger" id="hamburgerBtn" aria-label="منو">
			<span></span>
			<span></span>
			<span></span>
		</button>
	</div>

	<div class="header__center">
		<!-- Space reserved for scrolled logo center -->
	</div>

	<div class="header__right">
		<a href="<?php echo esc_url( $cart_url ); ?>" class="header__icon" aria-label="سبد خرید">
			<svg viewBox="0 0 24 24">
				<path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18" />
				<circle cx="8" cy="10" r="2" />
				<circle cx="16" cy="10" r="2" />
			</svg>
		</a>
		<a href="<?php echo esc_url( $account_url ); ?>" class="header__icon" aria-label="حساب کاربری">
			<svg viewBox="0 0 24 24">
				<circle cx="12" cy="8" r="4" />
				<path d="M20 21a8 8 0 10-16 0" />
			</svg>
		</a>
	</div>
</header>

<!-- Floating Animated Logo -->
<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="floating-logo" id="floatingLogo">
	<span class="logo-icon">✦</span>
	<span class="logo-text"><?php echo esc_html( $brand_name ); ?></span>
</a>

<!-- Mobile Drawer Navigation -->
<div class="mobile-drawer" id="mobileDrawer">
	<div class="drawer-overlay" id="drawerOverlay"></div>
	<div class="drawer-content">
		<div class="drawer-header">
			<span class="drawer-title"><?php echo esc_html( $brand_name ); ?></span>
			<button class="drawer-close" id="drawerClose">&times;</button>
		</div>

		<?php if ( $enable_gtranslate && shortcode_exists( 'gtranslate' ) ) : ?>
			<div class="drawer-gtranslate-modern">
				<span class="gtranslate-label"><i class="fa-solid fa-language"></i> انتخاب زبان:</span>
				<?php echo do_shortcode( '[gtranslate]' ); ?>
			</div>
		<?php endif; ?>

		<nav class="drawer-nav">
			<?php
			if ( $header_menu_id ) {
				wp_nav_menu( array(
					'menu' => $header_menu_id,
					'container' => false,
					'menu_class' => 'drawer-menu-list',
				) );
			} else {
				wp_nav_menu( array(
					'theme_location' => 'primary_menu',
					'container' => false,
					'menu_class' => 'drawer-menu-list',
					'fallback_cb' => false,
				) );
			}
			?>
		</nav>
	</div>
</div>
