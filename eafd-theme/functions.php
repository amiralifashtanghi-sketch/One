<?php
/**
 * Theme functions and definitions for eafd-theme
 *
 * @package EAFD_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'EAFD_THEME_VERSION', '1.0.0' );
define( 'EAFD_THEME_DIR', get_template_directory() );
define( 'EAFD_THEME_URI', get_template_directory_uri() );

/**
 * Convert English numbers to Persian digits
 */
function eafd_convert_to_persian_digits( $string ) {
	$en = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' );
	$fa = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' );
	return str_replace( $en, $fa, (string) $string );
}

/**
 * Include Admin Options & SEO Performance
 */
require_once EAFD_THEME_DIR . '/inc/admin-options.php';
require_once EAFD_THEME_DIR . '/inc/seo-performance.php';

/**
 * Setup Theme Defaults and Support
 */
function eafd_theme_setup() {
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );

	register_nav_menus( array(
		'primary' => __( 'منوی اصلی هدر', 'eafd-theme' ),
		'footer'  => __( 'منوی دسترسی سریع فوتر', 'eafd-theme' ),
	) );

	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );

	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 400,
		'single_image_width'    => 800,
		'product_grid'          => array(
			'default_rows'    => 4,
			'min_rows'        => 1,
			'default_columns' => 4,
			'min_columns'     => 2,
			'max_columns'     => 5,
		),
	) );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'eafd_theme_setup' );

/**
 * Enqueue Scripts and Styles
 */
function eafd_theme_scripts() {
	wp_enqueue_style( 'vazirmatn-font', EAFD_THEME_URI . '/assets/fonts/vazirmatn.css', array(), EAFD_THEME_VERSION );
	wp_enqueue_style( 'eafd-main-style', EAFD_THEME_URI . '/assets/css/main.css', array( 'vazirmatn-font' ), EAFD_THEME_VERSION );

	wp_enqueue_script( 'eafd-ajax-cart', EAFD_THEME_URI . '/assets/js/ajax-cart.js', array( 'jquery' ), EAFD_THEME_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );

	wp_localize_script( 'eafd-ajax-cart', 'eafd_cart_params', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'eafd_cart_nonce' ),
		'cart_url' => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '#',
	) );
}
add_action( 'wp_enqueue_scripts', 'eafd_theme_scripts' );

/**
 * Inject Dynamic CSS Variables based on Admin Panel Options
 */
function eafd_theme_dynamic_css() {
	$primary_color = eafd_get_option( 'primary_color', '#ff8a00' );
	$bg_color      = eafd_get_option( 'bg_color', '#e3e7ee' );
	$card_bg       = eafd_get_option( 'card_bg', '#ffffff' );
	$text_color    = eafd_get_option( 'text_color', '#2d3748' );

	?>
	<style id="eafd-dynamic-colors">
		:root {
			--eafd-primary: <?php echo esc_html( $primary_color ); ?>;
			--eafd-primary-hover: <?php echo esc_html( $primary_color ); ?>dd;
			--eafd-bg: <?php echo esc_html( $bg_color ); ?>;
			--eafd-card-bg: <?php echo esc_html( $card_bg ); ?>;
			--eafd-text: <?php echo esc_html( $text_color ); ?>;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'eafd_theme_dynamic_css', 100 );

/**
 * WooCommerce Custom Cart Fragments for Floating Cart Badge & Modal
 */
if ( class_exists( 'WooCommerce' ) ) {
	add_filter( 'woocommerce_add_to_cart_fragments', 'eafd_cart_count_fragment' );
	function eafd_cart_count_fragment( $fragments ) {
		ob_start();
		$count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
		?>
		<span class="eafd-floating-cart-count" id="eafd-cart-badge"><?php echo esc_html( eafd_convert_to_persian_digits( $count ) ); ?></span>
		<?php
		$fragments['#eafd-cart-badge'] = ob_get_clean();

		ob_start();
		eafd_render_cart_drawer_content();
		$fragments['#eafd-cart-drawer-inner'] = ob_get_clean();

		return $fragments;
	}
}

/**
 * Render Ajax Cart Drawer Content
 */
function eafd_render_cart_drawer_content() {
	echo '<div id="eafd-cart-drawer-inner" class="eafd-cart-drawer-inner">';
	if ( ! class_exists( 'WooCommerce' ) || ! WC()->cart || WC()->cart->is_empty() ) {
		echo '<div class="eafd-empty-cart-msg"><p>سبد خرید شما خالی است.</p></div>';
	} else {
		echo '<ul class="eafd-mini-cart-list">';
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'thumbnail' ), $cart_item, $cart_item_key );
				$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
				$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
				?>
				<li class="eafd-cart-item">
					<div class="eafd-cart-item-thumb"><?php echo $thumbnail; ?></div>
					<div class="eafd-cart-item-details">
						<a class="eafd-cart-item-title" href="<?php echo esc_url( $product_permalink ); ?>"><?php echo esc_html( $product_name ); ?></a>
						<span class="eafd-cart-item-qty-price"><?php echo esc_html( eafd_convert_to_persian_digits( $cart_item['quantity'] ) ); ?> × <?php echo $product_price; ?></span>
					</div>
				</li>
				<?php
			}
		}
		echo '</ul>';
		echo '<div class="eafd-cart-drawer-footer">';
		echo '<div class="eafd-cart-total"><span>جمع کل:</span> <strong>' . WC()->cart->get_cart_subtotal() . '</strong></div>';
		echo '<div class="eafd-cart-actions">';
		echo '<a href="' . esc_url( wc_get_cart_url() ) . '" class="eafd-btn eafd-btn-outline">مشاهده سبد خرید</a>';
		echo '<a href="' . esc_url( wc_get_checkout_url() ) . '" class="eafd-btn eafd-btn-primary">تسویه حساب</a>';
		echo '</div>';
		echo '</div>';
	}
	echo '</div>';
}

/**
 * Ajax Handler to fetch cart drawer HTML
 */
function eafd_ajax_get_cart_drawer() {
	check_ajax_referer( 'eafd_cart_nonce', 'nonce' );
	eafd_render_cart_drawer_content();
	wp_die();
}
add_action( 'wp_ajax_eafd_get_cart_drawer', 'eafd_ajax_get_cart_drawer' );
add_action( 'wp_ajax_nopriv_eafd_get_cart_drawer', 'eafd_ajax_get_cart_drawer' );
