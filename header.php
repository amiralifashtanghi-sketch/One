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
$header_options    = get_option( 'kish_harmony_header_options', array() );
$brand_name        = ! empty( $header_options['brand_name'] ) ? $header_options['brand_name'] : 'کیش هارمونی';
$brand_logo        = ! empty( $header_options['logo_url'] ) ? $header_options['logo_url'] : '';
$header_menu_id    = ! empty( $header_options['header_menu_id'] ) ? $header_options['header_menu_id'] : 0;
$enable_gtranslate = isset( $header_options['enable_gtranslate'] ) ? $header_options['enable_gtranslate'] : '1';

// WooCommerce Account & Cart Links
$account_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account' );
$cart_url    = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart' );
$is_front    = is_front_page();
?>

<!-- Glassmorphic Fixed Header -->
<header class="header <?php echo ! $is_front ? 'internal-page-header' : ''; ?>" id="header">
	<div class="header__left">
		<button class="hamburger" id="hamburgerBtn" aria-label="منو" aria-expanded="false">
			<span></span>
			<span></span>
			<span></span>
		</button>
	</div>

	<div class="header__center">
		<?php if ( ! $is_front ) : ?>
			<!-- Static Center Logo for Internal Pages -->
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header-logo">
				<?php if ( ! empty( $brand_logo ) ) : ?>
					<img src="<?php echo esc_url( $brand_logo ); ?>" alt="<?php echo esc_attr( $brand_name ); ?>" loading="eager" fetchpriority="high" style="max-height:36px;">
				<?php else : ?>
					<span class="logo-icon">✦</span>
				<?php endif; ?>
				<span class="logo-text"><?php echo esc_html( $brand_name ); ?></span>
			</a>
		<?php endif; ?>
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

<?php if ( $is_front ) : ?>
	<!-- Floating Animated Logo (Only on Front Page) -->
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="floating-logo" id="floatingLogo">
		<?php if ( ! empty( $brand_logo ) ) : ?>
			<img src="<?php echo esc_url( $brand_logo ); ?>" alt="<?php echo esc_attr( $brand_name ); ?>" class="header-logo-img" loading="eager" fetchpriority="high">
		<?php else : ?>
			<span class="logo-icon">✦</span>
		<?php endif; ?>
		<span class="logo-text"><?php echo esc_html( $brand_name ); ?></span>
	</a>
<?php endif; ?>

<!-- Overlay for Mobile Drawer -->
<div class="menu-overlay" id="menuOverlay"></div>

<!-- Modern Drawer Navigation -->
<nav class="drawer" id="drawerMenu" aria-label="منوی اصلی">
	<div class="drawer-header">
		<div class="title">
			<?php if ( ! empty( $brand_logo ) ) : ?>
				<img src="<?php echo esc_url( $brand_logo ); ?>" alt="<?php echo esc_attr( $brand_name ); ?>" style="max-height:28px; width:auto; border-radius:4px;">
			<?php else : ?>
				<span class="brand-icon">✦</span>
			<?php endif; ?>
			منوی <?php echo esc_html( $brand_name ); ?>
		</div>
		<button class="drawer-close" id="drawerClose" aria-label="بستن منو">✕</button>
	</div>

	<?php if ( $enable_gtranslate && shortcode_exists( 'gtranslate' ) ) : ?>
		<div class="drawer-gtranslate-modern">
			<span class="gtranslate-label"><i class="fa-solid fa-language"></i> انتخاب زبان:</span>
			<?php echo do_shortcode( '[gtranslate]' ); ?>
		</div>
	<?php endif; ?>

	<div class="drawer-nav">
		<?php
		if ( $header_menu_id ) {
			wp_nav_menu( array(
				'menu'        => $header_menu_id,
				'container'   => false,
				'menu_class'  => 'menu-list',
				'fallback_cb' => false,
			) );
		} else {
			wp_nav_menu( array(
				'theme_location' => 'primary_menu',
				'container'      => false,
				'menu_class'     => 'menu-list',
				'fallback_cb'    => false,
			) );
		}
		?>
	</div>

	<div class="drawer-footer-note">
		<small><?php echo esc_html( $brand_name ); ?> - همراه سفرهای شما در کیش</small>
	</div>
</nav>
