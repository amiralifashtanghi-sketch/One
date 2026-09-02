<?php
/**
 * SEO, Schema.org JSON-LD and Performance Optimizations
 *
 * @package EAFD_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output Schema.org Structured Data for Store & Products
 */
function eafd_output_schema_json_ld() {
	$brand_title = eafd_get_option( 'brand_title', 'محصولات ارگانیک سجاد برزویی' );
	$logo_url    = eafd_get_option( 'logo_url', '' );
	$phone       = eafd_get_option( 'footer_phone', '' );
	$address     = eafd_get_option( 'footer_address', '' );

	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Store',
		'name'     => $brand_title,
		'url'      => home_url( '/' ),
	);

	if ( $logo_url ) {
		$schema['image'] = $logo_url;
	}

	if ( $phone ) {
		$schema['telephone'] = $phone;
	}

	if ( $address ) {
		$schema['address'] = array(
			'@type'          => 'PostalAddress',
			'streetAddress'  => $address,
			'addressCountry' => 'IR',
		);
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'eafd_output_schema_json_ld', 5 );

/**
 * Add Preload for Main Font
 */
function eafd_preload_font() {
	?>
	<link rel="preload" href="<?php echo esc_url( EAFD_THEME_URI . '/assets/fonts/Vazirmatn-Regular.woff2' ); ?>" as="font" type="font/woff2" crossorigin="anonymous">
	<?php
}
add_action( 'wp_head', 'eafd_preload_font', 1 );
