<?php
/**
 * Plugin Name: Smart Image Loading Engine
 * Plugin URI: https://example.com/smart-image-loading-engine
 * Description: A premium WordPress performance plugin focused ONLY on image loading optimization with smart scheduling and skeletons.
 * Version: 1.0.0
 * Author: Jules
 * Author URI: https://example.com
 * License: GPL2
 * Text Domain: smart-image-loading-engine
 * Requires PHP: 8.2
 * Requires at least: 6.8
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Define Constants
define('SILE_VERSION', '1.0.0');
define('SILE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SILE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SILE_BASENAME', plugin_basename(__FILE__));

/**
 * Main Plugin Class
 */
final class SmartImageLoadingEngine {

    /**
     * Singleton instance
     */
    private static $instance = null;

    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->autoloader();
        $this->init();
    }

    /**
     * Simple Autoloader
     */
    private function autoloader() {
        spl_autoload_register(function ($class) {
            $prefix = 'SILE\\';
            $base_dir = SILE_PLUGIN_DIR . 'inc/';

            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                return;
            }

            $relative_class = substr($class, $len);
            $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

            if (file_exists($file)) {
                require $file;
            }
        });
    }

    /**
     * Initialize the plugin
     */
    private function init() {
        // Initialize Admin Panel
        if (is_admin()) {
            new SILE\Admin\Settings();
        }

        // Initialize Core Engine
        new SILE\Core\Engine();
    }
}

/**
 * Activation Hook
 */
register_activation_hook(__FILE__, function () {
    // Set default options if not exists
    if (!get_option('sile_settings')) {
        update_option('sile_settings', [
            'enabled' => 'yes',
            'skeleton' => 'yes',
            'animation_speed' => 450,
            'fade_duration' => 400,
            'intersection_margin' => 300,
            'concurrent_downloads' => 4,
            'enable_queue' => 'yes',
            'enable_bg_images' => 'yes',
            'enable_idle_preload' => 'yes',
            'debug_mode' => 'no'
        ]);
    }
});

/**
 * Deactivation Hook
 */
register_deactivation_hook(__FILE__, function () {
    flush_rewrite_rules();
});

// Start the engine
function sile_init() {
    return SmartImageLoadingEngine::get_instance();
}

add_action('plugins_loaded', 'sile_init');
