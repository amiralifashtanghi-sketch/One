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
function kish_harmony_scripts() {
	// Vazirmatn Font CDN
	wp_enqueue_style( 'vazirmatn-font', 'https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css', array(), '33.003' );

	// Font Awesome CDN
	wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0' );

	// Main Theme Style
	wp_enqueue_style( 'kish-harmony-style', get_stylesheet_uri(), array(), KISH_HARMONY_VERSION );

	// Custom Theme Main CSS
	if ( file_exists( KISH_HARMONY_DIR . '/assets/css/main.css' ) ) {
		wp_enqueue_style( 'kish-harmony-main', KISH_HARMONY_URI . '/assets/css/main.css', array(), KISH_HARMONY_VERSION );
	}

	// Main JS
	if ( file_exists( KISH_HARMONY_DIR . '/assets/js/main.js' ) ) {
		wp_enqueue_script( 'kish-harmony-main', KISH_HARMONY_URI . '/assets/js/main.js', array(), KISH_HARMONY_VERSION, true );

		wp_localize_script( 'kish-harmony-main', 'kishHarmonyData', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'kish_harmony_ajax_nonce' ),
		) );
	}
}
add_action( 'wp_enqueue_scripts', 'kish_harmony_scripts' );

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
require_once KISH_HARMONY_DIR . '/inc/admin-footer.php';
require_once KISH_HARMONY_DIR . '/inc/custom-post-types.php';
require_once KISH_HARMONY_DIR . '/inc/woocommerce-meta.php';
require_once KISH_HARMONY_DIR . '/inc/theme-helpers.php';
