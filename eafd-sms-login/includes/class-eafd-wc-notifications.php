<?php
if (!defined('ABSPATH')) {
    exit;
}

class EAFD_WC_Notifications {

    public function __construct() {
        // Send SMS when order status changes or is created
        add_action('woocommerce_order_status_changed', array($this, 'on_order_status_changed'), 10, 4);
        add_action('woocommerce_new_order', array($this, 'on_new_order_created'), 10, 2);
    }

    public function on_new_order_created($order_id, $order = null) {
        if (!$order) {
            $order = wc_get_order($order_id);
        }
        if (!$order) {
            return;
        }

        // Send admin notification
        $this->send_admin_new_order_sms($order);
    }

    public function on_order_status_changed($order_id, $old_status, $new_status, $order) {
        if (!$order) {
            return;
        }

        // Send customer order status update SMS
        $this->send_customer_order_sms($order, $new_status);
    }

    private function send_customer_order_sms($order, $status) {
        $settings = get_option('eafd_sms_settings', []);
        $template_id = $settings['order_customer_template_id'] ?? '';

        if (empty($template_id)) {
            return;
        }

        $phone = $order->get_billing_phone();
        $normalized = EAFD_Phone_Helper::normalize_phone($phone);

        if (!$normalized) {
            return;
        }

        $var_site = !empty($settings['order_customer_var_site']) ? $settings['order_customer_var_site'] : 'SITE';
        $var_order = !empty($settings['order_customer_var_order']) ? $settings['order_customer_var_order'] : 'ORDER_ID';
        $var_status = !empty($settings['order_customer_var_status']) ? $settings['order_customer_var_status'] : 'STATUS';

        $status_name = wc_get_order_status_name($status);
        $site_title = get_bloginfo('name');

        $client = new EAFD_SMS_Client();
        $client->send_pattern($normalized, $template_id, [
            $var_site => $site_title,
            $var_order => $order->get_order_number(),
            $var_status => $status_name
        ]);
    }

    private function send_admin_new_order_sms($order) {
        $settings = get_option('eafd_sms_settings', []);
        $admin_phone = $settings['admin_phone'] ?? '';
        $template_id = $settings['order_admin_template_id'] ?? '';

        $normalized_admin = EAFD_Phone_Helper::normalize_phone($admin_phone);

        if (!$normalized_admin || empty($template_id)) {
            return;
        }

        $var_site = !empty($settings['order_admin_var_site']) ? $settings['order_admin_var_site'] : 'SITE';
        $var_order = !empty($settings['order_admin_var_order']) ? $settings['order_admin_var_order'] : 'ORDER_ID';

        $site_title = get_bloginfo('name');

        $client = new EAFD_SMS_Client();
        $client->send_pattern($normalized_admin, $template_id, [
            $var_site => $site_title,
            $var_order => $order->get_order_number()
        ]);
    }
}

new EAFD_WC_Notifications();
