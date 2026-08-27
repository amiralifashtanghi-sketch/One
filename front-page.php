<?php
/**
 * Front Page Template for Kish Harmony Theme
 */
get_header();

// Section Rendering Loop in order with custom blocks support
$sections = array(
	'banner'         => 'templates/section-banner.php',
	'services'       => 'templates/section-services.php',
	'search'         => 'templates/section-search.php',
	'categories'     => 'templates/section-categories.php',
	'special_offers' => 'templates/section-special-offers.php',
	'car_rental'     => 'templates/section-car-rental.php',
	'kishpedia'      => 'templates/section-kishpedia.php',
	'weather'        => 'templates/section-weather.php',
	'gallery'        => 'templates/section-gallery.php',
);

echo '<main class="main-content-area">';

foreach ( $sections as $key => $template_path ) {
	if ( kish_harmony_is_section_enabled( $key ) ) {
		if ( file_exists( KISH_HARMONY_DIR . '/' . $template_path ) ) {
			include KISH_HARMONY_DIR . '/' . $template_path;
		}
		kish_harmony_render_custom_blocks_for( 'after_' . $key );
	}
}

echo '</main>';

get_footer();
