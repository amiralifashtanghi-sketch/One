<?php
/**
 * Kish Harmony Theme Functions
 *
 * @package Kish_Harmony
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'KISH_HARMONY_VERSION', '1.0.0' );
define( 'KISH_HARMONY_DIR', get_template_directory() );
define( 'KISH_HARMONY_URI', get_template_directory_uri() );

/**
 * Setup theme defaults and register support for WordPress features.
 */
function kish_harmony_setup() {
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// Register Navigation Menus
	register_nav_menus( array(
		'primary_menu' => __( 'فهرست اصلی هدر', 'kish-harmony' ),
		'footer_menu'  => __( 'فهرست فوتر', 'kish-harmony' ),
	) );

	// HTML5 markup support
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );

	// WooCommerce Support
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'kish_harmony_setup' );

/**
 * Enqueue scripts and styles.
 */
/**
 * Enqueue Admin Scripts & Styles (Color Picker)
 */
function kish_harmony_admin_scripts( $hook ) {
	if ( strpos( $hook, 'kish-harmony' ) !== false ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
	}
}
add_action( 'admin_enqueue_scripts', 'kish_harmony_admin_scripts' );

function kish_harmony_scripts() {
	// Local Vazirmatn Font
	if ( file_exists( KISH_HARMONY_DIR . '/assets/fonts/vazirmatn.css' ) ) {
		wp_enqueue_style( 'vazirmatn-font-local', KISH_HARMONY_URI . '/assets/fonts/vazirmatn.css', array(), '33.003' );
	} else {
		wp_enqueue_style( 'vazirmatn-font-cdn', 'https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css', array(), '33.003' );
	}

	// Font Awesome CDN
	wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0' );

	// Main Theme Style
	wp_enqueue_style( 'kish-harmony-style', get_stylesheet_uri(), array(), KISH_HARMONY_VERSION );

	// Custom Theme Main CSS
	if ( file_exists( KISH_HARMONY_DIR . '/assets/css/main.css' ) ) {
		wp_enqueue_style( 'kish-harmony-main', KISH_HARMONY_URI . '/assets/css/main.css', array(), KISH_HARMONY_VERSION );
	}

	// Dynamic Corporate Color Injection
	$general_options = get_option( 'kish_harmony_general_options', array() );
	$primary_color   = ! empty( $general_options['primary_color'] ) ? $general_options['primary_color'] : '#0B63D8';
	$secondary_color = ! empty( $general_options['secondary_color'] ) ? $general_options['secondary_color'] : '#18D6D8';
	$accent_color    = ! empty( $general_options['accent_color'] ) ? $general_options['accent_color'] : '#FF8A00';

	$custom_css = "
		:root {
			--kh-primary-blue: {$primary_color} !important;
			--blue-deep: {$primary_color} !important;
			--deep-blue: {$primary_color} !important;
			--kh-turquoise: {$secondary_color} !important;
			--teal: {$secondary_color} !important;
			--turquoise: {$secondary_color} !important;
			--kh-orange: {$accent_color} !important;
			--orange: {$accent_color} !important;
			--accent-orange: {$accent_color} !important;
		}
		.site-header { background: linear-gradient(135deg, {$primary_color} 0%, {$secondary_color} 100%) !important; }
		.site-footer { background: {$primary_color} !important; }
		.hero-section { background: linear-gradient(135deg, {$primary_color} 0%, {$secondary_color} 100%) !important; }
		.hero-card, .service-card { border-top: 3px solid {$secondary_color} !important; }
		.hero-card:hover, .service-card:hover { border-color: {$accent_color} !important; }
	";
	wp_add_inline_style( 'kish-harmony-main', $custom_css );

	// Main JS
	if ( file_exists( KISH_HARMONY_DIR . '/assets/js/main.js' ) ) {
		wp_enqueue_script( 'kish-harmony-main', KISH_HARMONY_URI . '/assets/js/main.js', array(), KISH_HARMONY_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );

		wp_localize_script( 'kish-harmony-main', 'kishHarmonyData', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'kish_harmony_ajax_nonce' ),
		) );
	}
}
add_action( 'wp_enqueue_scripts', 'kish_harmony_scripts' );

/**
 * Add Preconnect & DNS Prefetch Resource Hints for fast external loading.
 */
function kish_harmony_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type || 'dns-prefetch' === $relation_type ) {
		$urls[] = 'https://cdnjs.cloudflare.com';
		$urls[] = 'https://cdn.jsdelivr.net';
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'kish_harmony_resource_hints', 10, 2 );

/**
 * Flush Theme Transients on Content Changes for Performance Cache Management.
 */
function kish_harmony_flush_transients() {
	delete_transient( 'kish_harmony_special_offers_query' );
	delete_transient( 'kish_harmony_car_rentals_query' );
	delete_transient( 'kish_harmony_categories_query' );
}
add_action( 'save_post', 'kish_harmony_flush_transients' );
add_action( 'woocommerce_update_product', 'kish_harmony_flush_transients' );

// Require Includes
require_once KISH_HARMONY_DIR . '/inc/admin-options.php';
require_once KISH_HARMONY_DIR . '/inc/admin-header.php';
require_once KISH_HARMONY_DIR . '/inc/admin-services.php';
require_once KISH_HARMONY_DIR . '/inc/admin-banner.php';
require_once KISH_HARMONY_DIR . '/inc/ajax-search.php';
require_once KISH_HARMONY_DIR . '/inc/admin-categories.php';
require_once KISH_HARMONY_DIR . '/inc/admin-special-offers.php';
require_once KISH_HARMONY_DIR . '/inc/admin-weather.php';
require_once KISH_HARMONY_DIR . '/inc/admin-gallery.php';
require_once KISH_HARMONY_DIR . '/inc/admin-kishpedia.php';
require_once KISH_HARMONY_DIR . '/inc/admin-car-rental.php';
require_once KISH_HARMONY_DIR . '/inc/admin-footer.php';
require_once KISH_HARMONY_DIR . '/inc/custom-post-types.php';
require_once KISH_HARMONY_DIR . '/inc/woocommerce-meta.php';
require_once KISH_HARMONY_DIR . '/inc/admin-woocommerce.php';
require_once KISH_HARMONY_DIR . '/inc/theme-helpers.php';
