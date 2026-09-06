<?php
/**
 * Plugin Name: طراحی اختصاصی ووکامرس ساخته شده eafd.ir
 * Plugin URI: https://eafd.ir
 * Description: افزونه بازطراحی اختصاصی صفحات ووکامرس (حساب کاربری، سبد خرید، تسویه‌حساب و...) با سبک‌های ترکیبی گلاسمورفیسم، نیومورفیسم و اسکئومورفیسم، بی‌نیاز از قالب و ۱۰۰٪ لود محلی.
 * Version: 1.0.0
 * Author: eafd.ir
 * Author URI: https://eafd.ir
 * Text Domain: eafd-custom-wc
 * Domain Path: /languages
 * WC requires at least: 6.0
 * WC tests up to: 9.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

define('EAFD_WC_DESIGN_VERSION', '1.0.0');
define('EAFD_WC_DESIGN_FILE', __FILE__);
define('EAFD_WC_DESIGN_PATH', plugin_dir_path(__FILE__));
define('EAFD_WC_DESIGN_URL', plugin_dir_url(__FILE__));

require_once EAFD_WC_DESIGN_PATH . 'inc/class-eafd-wc-design.php';

function EAFD_WC_Design() {
    return EAFD_WC_Design::get_instance();
}

EAFD_WC_Design();
