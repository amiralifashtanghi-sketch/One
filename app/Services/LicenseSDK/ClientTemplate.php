<?php
/**
 * EAFD Standard License Client SDK
 * Embedded inside protected WordPress plugins and themes.
 */

if (!class_exists('EAFD_License_Client')) {

    class EAFD_License_Client {
        private string $product_id;
        private string $product_name;
        private string $current_version;
        private string $api_url;
        private string $option_key;

        public function __construct(string $product_id, string $product_name, string $current_version, string $api_url) {
            $this->product_id = $product_id;
            $this->product_name = $product_name;
            $this->current_version = $current_version;
            $this->api_url = rtrim($api_url, '/');
            $this->option_key = 'eafd_license_' . sanitize_key($product_id);

            if (is_admin()) {
                add_action('admin_menu', [$this, 'register_admin_menu']);
                add_action('admin_init', [$this, 'handle_license_action']);
                add_filter('pre_set_site_transient_update_plugins', [$this, 'check_for_updates']);
            }
        }

        public function get_license_data(): array {
            $data = get_option($this->option_key, []);
            if (!is_array($data)) {
                $data = [];
            }
            return array_merge([
                'license_key' => '',
                'status' => 'inactive',
                'last_check' => 0,
                'grace_until' => 0,
            ], $data);
        }

        public function is_active(): bool {
            $data = $this->get_license_data();
            if ($data['status'] === 'active') {
                return true;
            }
            // Check Grace Period
            if ($data['grace_until'] > time()) {
                return true;
            }
            return false;
        }

        public function register_admin_menu(): void {
            add_options_page(
                'لایسنس ' . esc_html($this->product_name),
                'لایسنس ' . esc_html($this->product_name),
                'manage_options',
                'eafd-license-' . sanitize_key($this->product_id),
                [$this, 'render_admin_page']
            );
        }

        public function render_admin_page(): void {
            $data = $this->get_license_data();
            $message = '';
            if (isset($_GET['eafd_msg'])) {
                $message = sanitize_text_field($_GET['eafd_msg']);
            }
            ?>
            <div class="wrap" dir="rtl" style="font-family: Tahoma, sans-serif; max-width: 600px; margin: 30px auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
                <h2>مدیریت لایسنس: <?php echo esc_html($this->product_name); ?></h2>
                <?php if ($message): ?>
                    <div class="notice notice-info"><p><?php echo esc_html($message); ?></p></div>
                <?php endif; ?>
                <form method="post" action="">
                    <?php wp_nonce_field('eafd_license_action', 'eafd_nonce'); ?>
                    <p>
                        <label>کلید لایسنس (EAFD-XXXX-XXXX-XXXX):</label><br>
                        <input type="text" name="eafd_license_key" value="<?php echo esc_attr($data['license_key']); ?>" class="regular-text" style="width:100%; direction:ltr; font-family:monospace; margin-top:5px;" required>
                    </p>
                    <p><strong>وضعیت فعلی:</strong>
                        <?php if ($this->is_active()): ?>
                            <span style="color:#10b981; font-weight:bold;">✔ فعال</span>
                        <?php else: ?>
                            <span style="color:#ef4444; font-weight:bold;">✖ غیرفعال</span>
                        <?php endif; ?>
                    </p>
                    <p>
                        <?php if ($data['status'] === 'active'): ?>
                            <button type="submit" name="eafd_action" value="deactivate" class="button button-secondary">غیرفعال‌سازی لایسنس</button>
                        <?php else: ?>
                            <button type="submit" name="eafd_action" value="activate" class="button button-primary">فعال‌سازی لایسنس</button>
                        <?php endif; ?>
                    </p>
                </form>
            </div>
            <?php
        }

        public function handle_license_action(): void {
            if (!isset($_POST['eafd_action']) || !isset($_POST['eafd_nonce'])) {
                return;
            }
            if (!wp_verify_nonce($_POST['eafd_nonce'], 'eafd_license_action')) {
                return;
            }

            $action = sanitize_text_field($_POST['eafd_action']);
            $key = sanitize_text_field($_POST['eafd_license_key'] ?? '');
            $domain = $_SERVER['HTTP_HOST'] ?? 'localhost';

            if ($action === 'activate') {
                $response = wp_remote_post($this->api_url . '/api/v1/license/activate', [
                    'body' => [
                        'license_key' => $key,
                        'product_id' => $this->product_id,
                        'domain' => $domain,
                    ],
                    'timeout' => 15,
                ]);

                if (is_wp_error($response)) {
                    $msg = 'خطا در ارتباط با سرور لایسنس EAFD: ' . $response->get_error_message();
                } else {
                    $body = json_decode(wp_remote_retrieve_body($response), true);
                    if (!empty($body['success'])) {
                        $graceDays = $body['grace_period_days'] ?? 7;
                        update_option($this->option_key, [
                            'license_key' => $key,
                            'status' => 'active',
                            'last_check' => time(),
                            'grace_until' => time() + ($graceDays * 86400),
                        ]);
                        $msg = 'لایسنس با موفقیت فعال شد.';
                    } else {
                        $msg = 'خطا: ' . ($body['message'] ?? 'لایسنس معتبر نیست.');
                    }
                }

                wp_redirect(add_query_arg(['page' => 'eafd-license-' . sanitize_key($this->product_id), 'eafd_msg' => urlencode($msg)], admin_url('options-general.php')));
                exit;
            }

            if ($action === 'deactivate') {
                $data = $this->get_license_data();
                wp_remote_post($this->api_url . '/api/v1/license/deactivate', [
                    'body' => [
                        'license_key' => $data['license_key'],
                        'product_id' => $this->product_id,
                        'domain' => $domain,
                    ],
                    'timeout' => 10,
                ]);

                update_option($this->option_key, [
                    'license_key' => '',
                    'status' => 'inactive',
                    'last_check' => time(),
                    'grace_until' => 0,
                ]);

                wp_redirect(add_query_arg(['page' => 'eafd-license-' . sanitize_key($this->product_id), 'eafd_msg' => urlencode('لایسنس غیرفعال شد.')], admin_url('options-general.php')));
                exit;
            }
        }

        public function check_for_updates($transient) {
            if (empty($transient->checked)) {
                return $transient;
            }

            $data = $this->get_license_data();
            if (!$this->is_active()) {
                return $transient;
            }

            $url = add_query_arg([
                'product_id' => $this->product_id,
                'license_key' => $data['license_key'],
                'current_version' => $this->current_version,
            ], $this->api_url . '/api/v1/license/update');

            $response = wp_remote_get($url, ['timeout' => 10]);
            if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
                $payload = json_decode(wp_remote_retrieve_body($response), true);
                if (!empty($payload['has_update'])) {
                    $obj = new stdClass();
                    $obj->slug = sanitize_key($this->product_id);
                    $obj->new_version = $payload['new_version'];
                    $obj->package = $payload['download_url'];
                    $obj->url = $payload['changelog_url'] ?? $this->api_url;

                    $transient->response[$obj->slug] = $obj;
                }
            }

            return $transient;
        }
    }
}
