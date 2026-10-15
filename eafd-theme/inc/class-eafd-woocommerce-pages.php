<?php
/**
 * WooCommerce Pages Resolver & Management Class for EAFD Theme
 *
 * @package EAFD_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class EAFD_WooCommerce_Pages {

	/**
	 * Instance of this class
	 *
	 * @var EAFD_WooCommerce_Pages|null
	 */
	private static $instance = null;

	/**
	 * Required core WooCommerce page keys and default post titles/slugs/shortcodes
	 *
	 * @var array
	 */
	private $core_pages = array(
		'cart'      => array(
			'title'     => 'سبد خرید',
			'slug'      => 'cart',
			'shortcode' => '[woocommerce_cart]',
			'option'    => 'woocommerce_cart_page_id',
		),
		'checkout'  => array(
			'title'     => 'تسویه حساب',
			'slug'      => 'checkout',
			'shortcode' => '[woocommerce_checkout]',
			'option'    => 'woocommerce_checkout_page_id',
		),
		'myaccount' => array(
			'title'     => 'حساب کاربری',
			'slug'      => 'my-account',
			'shortcode' => '[woocommerce_my_account]',
			'option'    => 'woocommerce_myaccount_page_id',
		),
		'shop'      => array(
			'title'     => 'فروشگاه',
			'slug'      => 'shop',
			'shortcode' => '',
			'option'    => 'woocommerce_shop_page_id',
		),
	);

	/**
	 * Get singleton instance
	 *
	 * @return EAFD_WooCommerce_Pages
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		add_action( 'after_switch_theme', array( $this, 'auto_setup_pages' ) );
		add_action( 'admin_init', array( $this, 'maybe_auto_repair_pages' ) );
	}

	/**
	 * Get WooCommerce Page ID with fallbacks
	 *
	 * @param string $page_key
	 * @return int
	 */
	public function get_page_id( $page_key ) {
		if ( ! isset( $this->core_pages[ $page_key ] ) ) {
			return 0;
		}

		$option_name = $this->core_pages[ $page_key ]['option'];
		$page_id     = (int) get_option( $option_name, 0 );

		if ( $page_id > 0 && get_post_status( $page_id ) === 'publish' ) {
			return $page_id;
		}

		if ( function_exists( 'wc_get_page_id' ) ) {
			$wc_id = wc_get_page_id( $page_key );
			if ( $wc_id > 0 && get_post_status( $wc_id ) === 'publish' ) {
				update_option( $option_name, $wc_id );
				return $wc_id;
			}
		}

		// Try finding by slug
		$page = get_page_by_path( $this->core_pages[ $page_key ]['slug'] );
		if ( $page && 'publish' === $page->post_status ) {
			update_option( $option_name, $page->ID );
			return $page->ID;
		}

		// Try finding by shortcode if applicable
		$shortcode = $this->core_pages[ $page_key ]['shortcode'];
		if ( ! empty( $shortcode ) ) {
			global $wpdb;
			$found_id = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT ID FROM {$wpdb->posts} WHERE post_type = 'page' AND post_status = 'publish' AND post_content LIKE %s LIMIT 1",
					'%' . $wpdb->esc_like( $shortcode ) . '%'
				)
			);
			if ( $found_id ) {
				update_option( $option_name, (int) $found_id );
				return (int) $found_id;
			}
		}

		return 0;
	}

	/**
	 * Get WooCommerce Page URL with safe fallbacks (never homepage unless specifically configured)
	 *
	 * @param string $page_key
	 * @return string
	 */
	public function get_page_url( $page_key ) {
		if ( ! isset( $this->core_pages[ $page_key ] ) ) {
			return site_url( '/' );
		}

		// Direct WooCommerce API check
		if ( function_exists( 'wc_get_page_permalink' ) ) {
			$url = wc_get_page_permalink( $page_key );
			if ( ! empty( $url ) && $url !== site_url( '/' ) && $url !== home_url( '/' ) ) {
				return $url;
			}
		}

		$page_id = $this->get_page_id( $page_key );
		if ( $page_id > 0 ) {
			$permalink = get_permalink( $page_id );
			if ( $permalink ) {
				return $permalink;
			}
		}

		// Safe fallback URL based on default slug
		return site_url( '/' . $this->core_pages[ $page_key ]['slug'] . '/' );
	}

	/**
	 * Auto setup / create missing required WooCommerce pages
	 *
	 * @return array Status report of created/repaired pages
	 */
	public function auto_setup_pages() {
		$results = array(
			'created'  => array(),
			'repaired' => array(),
			'existing' => array(),
		);

		foreach ( $this->core_pages as $key => $config ) {
			$page_id = $this->get_page_id( $key );

			if ( $page_id > 0 ) {
				$results['existing'][ $key ] = $page_id;
				continue;
			}

			// Create the missing page
			$new_page_id = wp_insert_post( array(
				'post_title'     => $config['title'],
				'post_name'      => $config['slug'],
				'post_content'   => $config['shortcode'],
				'post_status'    => 'publish',
				'post_type'      => 'page',
				'comment_status' => 'closed',
			) );

			if ( $new_page_id && ! is_wp_error( $new_page_id ) ) {
				update_option( $config['option'], $new_page_id );
				$results['created'][ $key ] = $new_page_id;
			}
		}

		return $results;
	}

	/**
	 * Admin hook to check and repair pages if needed
	 */
	public function maybe_auto_repair_pages() {
		if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Check if auto-repaired flag set
		if ( get_option( 'eafd_wc_pages_initial_check' ) !== '1' ) {
			$this->auto_setup_pages();
			update_option( 'eafd_wc_pages_initial_check', '1' );
		}
	}

	/**
	 * Health Audit for Diagnostic Page
	 *
	 * @return array
	 */
	public function get_health_status() {
		$status = array();

		foreach ( $this->core_pages as $key => $config ) {
			$page_id = $this->get_page_id( $key );
			$url     = $this->get_page_url( $key );
			$exists  = ( $page_id > 0 && get_post_status( $page_id ) === 'publish' );

			$status[ $key ] = array(
				'title'     => $config['title'],
				'page_id'   => $page_id,
				'url'       => $url,
				'exists'    => $exists,
				'shortcode' => $config['shortcode'],
				'status'    => $exists ? 'ok' : 'missing',
			);
		}

		return $status;
	}
}

// Global Helper Functions
function eafd_wc_pages() {
	return EAFD_WooCommerce_Pages::get_instance();
}

function eafd_get_cart_url() {
	return eafd_wc_pages()->get_page_url( 'cart' );
}

function eafd_get_checkout_url() {
	return eafd_wc_pages()->get_page_url( 'checkout' );
}

function eafd_get_account_url() {
	return eafd_wc_pages()->get_page_url( 'myaccount' );
}

function eafd_get_shop_url() {
	return eafd_wc_pages()->get_page_url( 'shop' );
}
