<?php
namespace SILE\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class Settings {
    private $options;

    public function __construct() {
        add_action('admin_menu', [$this, 'add_plugin_page']);
        add_action('admin_init', [$this, 'page_init']);
    }

    public function add_plugin_page() {
        add_menu_page(
            'Smart Image Loading',
            'Smart Image',
            'manage_options',
            'sile-settings',
            [$this, 'create_admin_page'],
            'dashicons-images-alt2',
            100
        );
    }

    public function create_admin_page() {
        $this->options = get_option('sile_settings');
        ?>
        <div class="wrap">
            <h1>Smart Image Loading Engine Settings</h1>

            <div class="nav-tab-wrapper">
                <a href="#settings" class="nav-tab nav-tab-active">Settings</a>
                <a href="#stats" class="nav-tab">System Stats</a>
            </div>

            <div id="tab-settings" class="tab-content">
                <form method="post" action="options.php">
                <?php
                    settings_fields('sile_settings_group');
                    do_settings_sections('sile-settings-admin');
                    submit_button();
                ?>
                </form>
            </div>

            <div id="tab-stats" class="tab-content" style="display:none; margin-top: 20px;">
                <div class="card" style="max-width: 100%; margin-top: 0;">
                    <h2>Engine Diagnostics</h2>
                    <p>Current Plugin Version: <b><?php echo SILE_VERSION; ?></b></p>
                    <p>PHP Version: <b><?php echo phpversion(); ?></b></p>
                    <p>Memory Limit: <b><?php echo ini_get('memory_limit'); ?></b></p>
                    <hr>
                    <p>Processed Images in Cache: <b><?php echo $this->get_cache_stats(); ?></b></p>
                </div>
            </div>

            <script>
                document.querySelectorAll('.nav-tab').forEach(tab => {
                    tab.addEventListener('click', function(e) {
                        e.preventDefault();
                        document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('nav-tab-active'));
                        document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');

                        this.classList.add('nav-tab-active');
                        const target = this.getAttribute('href').replace('#', 'tab-');
                        document.getElementById(target).style.display = 'block';
                    });
                });
            </script>
        </div>
        <?php
    }

    private function get_cache_stats() {
        global $wpdb;
        $count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->postmeta WHERE meta_key = '_wp_attached_file'");
        return $count ? $count : '0';
    }

    public function page_init() {
        register_setting(
            'sile_settings_group',
            'sile_settings',
            [$this, 'sanitize']
        );

        add_settings_section(
            'sile_setting_section_main',
            'General Settings',
            [$this, 'print_section_info'],
            'sile-settings-admin'
        );

        add_settings_field(
            'enabled',
            'Enable Plugin',
            [$this, 'enabled_callback'],
            'sile-settings-admin',
            'sile_setting_section_main'
        );

        add_settings_field(
            'skeleton',
            'Enable Skeleton Placeholder',
            [$this, 'skeleton_callback'],
            'sile-settings-admin',
            'sile_setting_section_main'
        );

        add_settings_field(
            'animation_speed',
            'Animation Speed (ms)',
            [$this, 'animation_speed_callback'],
            'sile-settings-admin',
            'sile_setting_section_main'
        );

        add_settings_field(
            'fade_duration',
            'Fade Duration (ms)',
            [$this, 'fade_duration_callback'],
            'sile-settings-admin',
            'sile_setting_section_main'
        );

        add_settings_field(
            'intersection_margin',
            'Intersection Margin (px)',
            [$this, 'intersection_margin_callback'],
            'sile-settings-admin',
            'sile_setting_section_main'
        );

        add_settings_field(
            'concurrent_downloads',
            'Max Concurrent Downloads',
            [$this, 'concurrent_downloads_callback'],
            'sile-settings-admin',
            'sile_setting_section_main'
        );

        add_settings_field(
            'enable_queue',
            'Enable Smart Queue',
            [$this, 'enable_queue_callback'],
            'sile-settings-admin',
            'sile_setting_section_main'
        );

        add_settings_field(
            'enable_bg_images',
            'Enable Background Image Support',
            [$this, 'enable_bg_images_callback'],
            'sile-settings-admin',
            'sile_setting_section_main'
        );

        add_settings_field(
            'enable_idle_preload',
            'Enable Idle Preload',
            [$this, 'enable_idle_preload_callback'],
            'sile-settings-admin',
            'sile_setting_section_main'
        );

        add_settings_field(
            'debug_mode',
            'Enable Debug Mode',
            [$this, 'debug_mode_callback'],
            'sile-settings-admin',
            'sile_setting_section_main'
        );
    }

    public function sanitize($input) {
        $new_input = [];
        $new_input['enabled'] = isset($input['enabled']) ? 'yes' : 'no';
        $new_input['skeleton'] = isset($input['skeleton']) ? 'yes' : 'no';
        $new_input['animation_speed'] = absint($input['animation_speed']);
        $new_input['fade_duration'] = absint($input['fade_duration']);
        $new_input['intersection_margin'] = absint($input['intersection_margin']);
        $new_input['concurrent_downloads'] = absint($input['concurrent_downloads']);
        $new_input['enable_queue'] = isset($input['enable_queue']) ? 'yes' : 'no';
        $new_input['enable_bg_images'] = isset($input['enable_bg_images']) ? 'yes' : 'no';
        $new_input['enable_idle_preload'] = isset($input['enable_idle_preload']) ? 'yes' : 'no';
        $new_input['debug_mode'] = isset($input['debug_mode']) ? 'yes' : 'no';

        return $new_input;
    }

    public function print_section_info() {
        echo 'Configure the core behavior of the Smart Image Loading Engine.';
    }

    public function enabled_callback() {
        printf('<input type="checkbox" name="sile_settings[enabled]" value="yes" %s />', isset($this->options['enabled']) && $this->options['enabled'] === 'yes' ? 'checked' : '');
    }

    public function skeleton_callback() {
        printf('<input type="checkbox" name="sile_settings[skeleton]" value="yes" %s />', isset($this->options['skeleton']) && $this->options['skeleton'] === 'yes' ? 'checked' : '');
    }

    public function animation_speed_callback() {
        printf('<input type="number" name="sile_settings[animation_speed]" value="%s" />', isset($this->options['animation_speed']) ? esc_attr($this->options['animation_speed']) : '450');
    }

    public function fade_duration_callback() {
        printf('<input type="number" name="sile_settings[fade_duration]" value="%s" />', isset($this->options['fade_duration']) ? esc_attr($this->options['fade_duration']) : '400');
    }

    public function intersection_margin_callback() {
        printf('<input type="number" name="sile_settings[intersection_margin]" value="%s" />', isset($this->options['intersection_margin']) ? esc_attr($this->options['intersection_margin']) : '300');
    }

    public function concurrent_downloads_callback() {
        printf('<input type="number" name="sile_settings[concurrent_downloads]" value="%s" />', isset($this->options['concurrent_downloads']) ? esc_attr($this->options['concurrent_downloads']) : '4');
    }

    public function enable_queue_callback() {
        printf('<input type="checkbox" name="sile_settings[enable_queue]" value="yes" %s />', isset($this->options['enable_queue']) && $this->options['enable_queue'] === 'yes' ? 'checked' : '');
    }

    public function enable_bg_images_callback() {
        printf('<input type="checkbox" name="sile_settings[enable_bg_images]" value="yes" %s />', isset($this->options['enable_bg_images']) && $this->options['enable_bg_images'] === 'yes' ? 'checked' : '');
    }

    public function enable_idle_preload_callback() {
        printf('<input type="checkbox" name="sile_settings[enable_idle_preload]" value="yes" %s />', isset($this->options['enable_idle_preload']) && $this->options['enable_idle_preload'] === 'yes' ? 'checked' : '');
    }

    public function debug_mode_callback() {
        printf('<input type="checkbox" name="sile_settings[debug_mode]" value="yes" %s />', isset($this->options['debug_mode']) && $this->options['debug_mode'] === 'yes' ? 'checked' : '');
    }
}
