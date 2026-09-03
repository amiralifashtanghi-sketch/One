<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<header id="masthead" class="site-header">
		<div class="eafd-container">
			<div class="eafd-header-card">
				<!-- Left: Hamburger Menu (Swapped for Plan 5) -->
				<div class="eafd-header-left">
					<button class="eafd-menu-toggle" aria-label="منوی اصلی" id="eafd-menu-toggle">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
					</button>
				</div>

				<!-- Center: Brand Title & Avatar/Logo -->
				<div class="eafd-header-center">
					<?php
					$brand_title = eafd_get_option( 'brand_title', 'محصولات ارگانیک سجاد برزویی' );
					$logo_url    = eafd_get_option( 'logo_url', '' );
					?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="eafd-brand-link">
						<span class="eafd-brand-name"><?php echo esc_html( $brand_title ); ?></span>
						<?php if ( $logo_url ) : ?>
							<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $brand_title ); ?>" class="eafd-brand-avatar" width="45" height="45" />
						<?php else : ?>
							<div class="eafd-brand-avatar-placeholder">
								<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
							</div>
						<?php endif; ?>
					</a>
				</div>

				<!-- Right: Account / User Icon (Swapped for Plan 5) -->
				<div class="eafd-header-right">
					<a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url() ); ?>" class="eafd-user-btn" aria-label="حساب کاربری">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
							<circle cx="12" cy="7" r="4"></circle>
						</svg>
					</a>
				</div>
			</div>
		</div>
	</header>
	<main id="primary" class="site-main">
