<?php
/**
 * Front Page Template for Kish Harmony Theme
 */
get_header();

$sections = array(
	'hero'           => 'templates/section-services.php',
	'banner'         => 'templates/section-banner.php',
	'search'         => 'templates/section-search.php',
	'categories'     => 'templates/section-categories.php',
	'special_offers' => 'templates/section-special-offers.php',
	'car_rental'     => 'templates/section-car-rental.php',
	'kishpedia'      => 'templates/section-kishpedia.php',
	'weather'        => 'templates/section-weather.php',
	'gallery'        => 'templates/section-gallery.php',
);

$general_options = get_option( 'kish_harmony_general_options', array() );
$order_setting   = ! empty( $general_options['section_order'] ) ? explode( ',', $general_options['section_order'] ) : array_keys( $sections );

echo '<main class="main-content-area">';

foreach ( $order_setting as $key ) {
	if ( isset( $sections[ $key ] ) && kish_harmony_is_section_enabled( $key ) ) {
		$template_path = $sections[ $key ];
		if ( file_exists( KISH_HARMONY_DIR . '/' . $template_path ) ) {
			include KISH_HARMONY_DIR . '/' . $template_path;
		}
		kish_harmony_render_custom_blocks_for( 'after_' . $key );
	}
}

echo '</main>';

get_footer();
