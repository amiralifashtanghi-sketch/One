<?php
if (!defined('ABSPATH')) {
    exit;
}

class EAFD_Admin_Settings {

    private static $instance = null;
    private $option_group = 'eafd_wc_design_options_group';
    private $option_name  = 'eafd_wc_design_options';

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    public static function get_default_options() {
        return array(
            // Account Sections Control
            'menu_dashboard_disable' => '0',
            'menu_orders_disable'    => '0',
            'menu_downloads_disable' => '0',
            'menu_edit_address_disable' => '0',
            'menu_edit_account_disable' => '0',
            'menu_logout_disable'    => '0',
            // 60-30-10 Color Scheme Defaults
            'color_primary'     => '#1a5276', // 60%
            'color_secondary'   => '#1abc9c', // 30%
            'color_accent'      => '#e67e22', // 10%
            'color_dark'        => '#0d1b2a',
            'color_light'       => '#f8fafc',
            'color_text'        => '#2c3e50',

            // Glassmorphism Defaults
            'glass_bg_opacity'  => '0.75',
            'glass_blur'        => '15',
            'glass_border_opacity' => '0.8',

            // Neomorphism Defaults
            'neo_bg_color'      => '#ecf0f1',
            'neo_radius'        => '18',
            'neo_shadow_intensity' => '0.12',

            // Visuals
            'enable_floating_blobs' => '1',
            'custom_logo_url'       => '',

            // Widgets Customization (Real Dynamic Data Titles)
            'widget_1_active'   => '1',
            'widget_1_title'    => 'تعداد کل سفارش‌ها',
            'widget_1_icon'     => 'fas fa-shopping-bag',
            'widget_1_image'    => '',

            'widget_2_active'   => '1',
            'widget_2_title'    => 'اقلام سبد خرید فعلی',
            'widget_2_icon'     => 'fas fa-shopping-cart',
            'widget_2_image'    => '',

            'widget_3_active'   => '1',
            'widget_3_title'    => 'مجموع خریدهای موفق',
            'widget_3_icon'     => 'fas fa-money-bill-wave',
            'widget_3_image'    => '',

            'widget_4_active'   => '1',
            'widget_4_title'    => 'نام کاربری شما',
            'widget_4_icon'     => 'fas fa-id-card',
            'widget_4_image'    => '',

            // Mobile & Theme Reset
            'enable_theme_reset'  => '1',
            'mobile_bottom_nav'   => '1',
            'mobile_side_drawer'  => '1',
        );
    }

    public function get_options() {
        $saved = get_option($this->option_name, array());
        return wp_parse_args($saved, self::get_default_options());
    }

    public function add_admin_menu() {
        add_menu_page(
            __('طراحی ظاهری ووکامرس', 'eafd-custom-wc'),
            __('طراحی ظاهری ووکامرس', 'eafd-custom-wc'),
            'manage_options',
            'eafd-wc-design',
            array($this, 'render_admin_page'),
            'dashicons-art',
            58
        );
    }

    public function register_settings() {
        register_setting($this->option_group, $this->option_name);
    }

    public function enqueue_admin_assets($hook) {
        if ($hook !== 'toplevel_page_eafd-wc-design') {
            return;
        }
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_media();
        wp_enqueue_style('eafd-font-vazirmatn', EAFD_WC_DESIGN_URL . 'assets/css/font-vazirmatn.css', array(), EAFD_WC_DESIGN_VERSION);
        wp_enqueue_style('eafd-fontawesome', EAFD_WC_DESIGN_URL . 'assets/css/fontawesome.css', array(), EAFD_WC_DESIGN_VERSION);
    }

    public function render_admin_page() {
        $options = $this->get_options();
        $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'colors';
        ?>
        <div class="wrap eafd-admin-wrap" style="direction: rtl; font-family: 'Vazirmatn', sans-serif;">
            <h1 style="font-weight: 800; color: #1a5276;">
                <i class="fas fa-paint-brush"></i> طراحی ظاهری ووکامرس <span style="font-size: 14px; font-weight: 400; color: #7f8c8d;">(ساخته شده توسط eafd.ir)</span>
            </h1>
            <h2 class="nav-tab-wrapper">
                <a href="?page=eafd-wc-design&tab=colors" class="nav-tab <?php echo $active_tab === 'colors' ? 'nav-tab-active' : ''; ?>">رنگ‌بندی ۶۰-۳۰-۱۰</a>
                <a href="?page=eafd-wc-design&tab=glass_neo" class="nav-tab <?php echo $active_tab === 'glass_neo' ? 'nav-tab-active' : ''; ?>">افکت‌های گلاس و نیومورفیسم</a>
                <a href="?page=eafd-wc-design&tab=visuals" class="nav-tab <?php echo $active_tab === 'visuals' ? 'nav-tab-active' : ''; ?>">لوگو و پس‌زمینه</a>
                <a href="?page=eafd-wc-design&tab=widgets" class="nav-tab <?php echo $active_tab === 'widgets' ? 'nav-tab-active' : ''; ?>">مدیریت ویجت‌های آمار داشبورد</a>
                <a href="?page=eafd-wc-design&tab=account_menu" class="nav-tab <?php echo $active_tab === 'account_menu' ? 'nav-tab-active' : ''; ?>">مدیریت سکشن‌های منوی حساب کاربری</a>
                <a href="?page=eafd-wc-design&tab=mobile_reset" class="nav-tab <?php echo $active_tab === 'mobile_reset' ? 'nav-tab-active' : ''; ?>">تنظیمات موبایل و خنثی‌سازی قالب</a>
            </h2>

            <form method="post" action="options.php" style="margin-top: 20px; background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <?php
                settings_fields($this->option_group);
                if ($active_tab === 'colors') {
                    $this->render_colors_tab($options);
                } elseif ($active_tab === 'glass_neo') {
                    $this->render_glass_neo_tab($options);
                } elseif ($active_tab === 'visuals') {
                    $this->render_visuals_tab($options);
                } elseif ($active_tab === 'widgets') {
                    $this->render_widgets_tab($options);
                } elseif ($active_tab === 'account_menu') {
                    $this->render_account_menu_tab($options);
                } elseif ($active_tab === 'mobile_reset') {
                    $this->render_mobile_reset_tab($options);
                }
                submit_button('ذخیره‌سازی تنظیمات');
                ?>
            </form>
        </div>
        <script>
        jQuery(document).ready(function($){
            $('.eafd-color-picker').wpColorPicker();
            $('.eafd-upload-btn').click(function(e){
                e.preventDefault();
                var button = $(this);
                var inputId = button.data('target');
                var mediaUploader = wp.media({
                    title: 'انتخاب تصویر',
                    button: { text: 'استفاده از این تصویر' },
                    multiple: false
                }).on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    $('#' + inputId).val(attachment.url);
                }).open();
            });
        });
        </script>
        <?php
    }

    private function render_colors_tab($options) {
        ?>
        <h3>تنظیمات پلت رنگ‌های سازمانی (قانون ۶۰ - ۳۰ - ۱۰)</h3>
        <p>در این بخش رنگ‌های اصلی، مکمل و آکسان را برای هارمونی کامل طراحی تعیین کنید.</p>
        <table class="form-table">
            <tr>
                <th scope="row">رنگ اصلی (۶۰٪):</th>
                <td>
                    <input type="text" name="<?php echo $this->option_name; ?>[color_primary]" value="<?php echo esc_attr($options['color_primary']); ?>" class="eafd-color-picker" />
                </td>
            </tr>
            <tr>
                <th scope="row">رنگ مکمل / ثانویه (۳۰٪):</th>
                <td>
                    <input type="text" name="<?php echo $this->option_name; ?>[color_secondary]" value="<?php echo esc_attr($options['color_secondary']); ?>" class="eafd-color-picker" />
                </td>
            </tr>
            <tr>
                <th scope="row">رنگ تأکیدی / آکسان (۱۰٪):</th>
                <td>
                    <input type="text" name="<?php echo $this->option_name; ?>[color_accent]" value="<?php echo esc_attr($options['color_accent']); ?>" class="eafd-color-picker" />
                </td>
            </tr>
        </table>
        <?php
    }

    private function render_glass_neo_tab($options) {
        ?>
        <h3>تنظیمات شفافیت شیشه‌ای و سایه‌های نیومورفیسم</h3>
        <table class="form-table">
            <tr>
                <th scope="row">میزان شفافیت پس‌زمینه شیشه‌ای (0 تا 1):</th>
                <td>
                    <input type="number" step="0.05" min="0.1" max="1" name="<?php echo $this->option_name; ?>[glass_bg_opacity]" value="<?php echo esc_attr($options['glass_bg_opacity']); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th scope="row">میزان تاری (Blur) شیشه‌ای (پیکسل):</th>
                <td>
                    <input type="number" min="0" max="50" name="<?php echo $this->option_name; ?>[glass_blur]" value="<?php echo esc_attr($options['glass_blur']); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th scope="row">شعاع انحنای کارت‌های نیومورفیسم (px):</th>
                <td>
                    <input type="number" min="0" max="40" name="<?php echo $this->option_name; ?>[neo_radius]" value="<?php echo esc_attr($options['neo_radius']); ?>" class="regular-text" />
                </td>
            </tr>
        </table>
        <?php
    }

    private function render_visuals_tab($options) {
        ?>
        <h3>تنظیمات لوگو و پس‌زمینه</h3>
        <table class="form-table">
            <tr>
                <th scope="row">لوگوی اختصاصی:</th>
                <td>
                    <input type="text" id="custom_logo_url" name="<?php echo $this->option_name; ?>[custom_logo_url]" value="<?php echo esc_url($options['custom_logo_url']); ?>" class="regular-text" />
                    <button type="button" class="button eafd-upload-btn" data-target="custom_logo_url">آپلود لوگو</button>
                </td>
            </tr>
            <tr>
                <th scope="row">پس‌زمینه‌های متحرک (Floating Blobs):</th>
                <td>
                    <label>
                        <input type="checkbox" name="<?php echo $this->option_name; ?>[enable_floating_blobs]" value="1" <?php checked($options['enable_floating_blobs'], '1'); ?> />
                        فعال‌سازی گلوله‌های رنگی متحرک در پس‌زمینه
                    </label>
                </td>
            </tr>
        </table>
        <?php
    }

    private function render_widgets_tab($options) {
        ?>
        <h3>مدیریت کارت‌های آمار واقعی داشبورد حساب کاربری</h3>
        <p>مقادیر این کارت‌ها به صورت کاملاً زنده و واقعی از ووکامرس (تعداد سفارشات، اقلام سبد خرید، مجموع خرید و نام کاربری) دریافت می‌شود.</p>
        <?php for ($i = 1; $i <= 4; $i++): ?>
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                <h4 style="margin-top:0;">کارت شماره <?php echo $i; ?></h4>
                <table class="form-table">
                    <tr>
                        <th scope="row">وضعیت کارت:</th>
                        <td>
                            <label>
                                <input type="checkbox" name="<?php echo $this->option_name; ?>[widget_<?php echo $i; ?>_active]" value="1" <?php checked(!empty($options['widget_' . $i . '_active']), '1'); ?> />
                                فعال باشد
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">عنوان کارت:</th>
                        <td><input type="text" name="<?php echo $this->option_name; ?>[widget_<?php echo $i; ?>_title]" value="<?php echo esc_attr($options['widget_' . $i . '_title']); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th scope="row">کلاس آیکون FontAwesome:</th>
                        <td><input type="text" name="<?php echo $this->option_name; ?>[widget_<?php echo $i; ?>_icon]" value="<?php echo esc_attr($options['widget_' . $i . '_icon']); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th scope="row">تصویر سفارشی (جایگزین آیکون):</th>
                        <td>
                            <input type="text" id="widget_<?php echo $i; ?>_image" name="<?php echo $this->option_name; ?>[widget_<?php echo $i; ?>_image]" value="<?php echo esc_url($options['widget_' . $i . '_image']); ?>" class="regular-text" />
                            <button type="button" class="button eafd-upload-btn" data-target="widget_<?php echo $i; ?>_image">آپلود عکس</button>
                        </td>
                    </tr>
                </table>
            </div>
        <?php endfor;
    }

    private function render_account_menu_tab($options) {
        ?>
        <h3>مدیریت و غیرفعال‌سازی سکشن‌های حساب کاربری ووکامرس</h3>
        <p>در این بخش می‌توانید هر یک از سکشن‌ها و لبه‌های منوی حساب کاربری ووکامرس را که نیاز ندارید غیرفعال کنید.</p>
        <table class="form-table">
            <tr>
                <th scope="row">پیشخوان (Dashboard):</th>
                <td>
                    <label>
                        <input type="checkbox" name="<?php echo $this->option_name; ?>[menu_dashboard_disable]" value="1" <?php checked(!empty($options['menu_dashboard_disable']), '1'); ?> />
                        مخفی و غیرفعال‌سازی لبه پیشخوان
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row">سفارش‌ها (Orders):</th>
                <td>
                    <label>
                        <input type="checkbox" name="<?php echo $this->option_name; ?>[menu_orders_disable]" value="1" <?php checked(!empty($options['menu_orders_disable']), '1'); ?> />
                        مخفی و غیرفعال‌سازی لبه سفارش‌ها
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row">دانلودها (Downloads):</th>
                <td>
                    <label>
                        <input type="checkbox" name="<?php echo $this->option_name; ?>[menu_downloads_disable]" value="1" <?php checked(!empty($options['menu_downloads_disable']), '1'); ?> />
                        مخفی و غیرفعال‌سازی لبه دانلودها
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row">آدرس‌ها (Addresses):</th>
                <td>
                    <label>
                        <input type="checkbox" name="<?php echo $this->option_name; ?>[menu_edit_address_disable]" value="1" <?php checked(!empty($options['menu_edit_address_disable']), '1'); ?> />
                        مخفی و غیرفعال‌سازی لبه آدرس‌ها
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row">جزئیات حساب (Account Details):</th>
                <td>
                    <label>
                        <input type="checkbox" name="<?php echo $this->option_name; ?>[menu_edit_account_disable]" value="1" <?php checked(!empty($options['menu_edit_account_disable']), '1'); ?> />
                        مخفی و غیرفعال‌سازی لبه ویرایش مشخصات حساب
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row">خروج (Logout):</th>
                <td>
                    <label>
                        <input type="checkbox" name="<?php echo $this->option_name; ?>[menu_logout_disable]" value="1" <?php checked(!empty($options['menu_logout_disable']), '1'); ?> />
                        مخفی کردن دکمه خروج
                    </label>
                </td>
            </tr>
        </table>
        <?php
    }

    private function render_mobile_reset_tab($options) {
        ?>
        <h3>تنظیمات موبایل و خنثی‌سازی استایل قالب</h3>
        <table class="form-table">
            <tr>
                <th scope="row">خنثی‌سازی استایل‌های قالب (Theme Reset):</th>
                <td>
                    <label>
                        <input type="checkbox" name="<?php echo $this->option_name; ?>[enable_theme_reset]" value="1" <?php checked(!empty($options['enable_theme_reset']), '1'); ?> />
                        غیرفعال‌سازی و خنثی‌سازی کامل استایل‌های قالب فعلی روی صفحات ووکامرس
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row">نوار ناوبری پایین صفحه (Bottom Navigation):</th>
                <td>
                    <label>
                        <input type="checkbox" name="<?php echo $this->option_name; ?>[mobile_bottom_nav]" value="1" <?php checked(!empty($options['mobile_bottom_nav']), '1'); ?> />
                        نمایش نوار دسترسی سریع پایین صفحه در نمایشگرهای موبایل
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row">سایدبار کشویی و منوی شناور (Sidebar Drawer):</th>
                <td>
                    <label>
                        <input type="checkbox" name="<?php echo $this->option_name; ?>[mobile_side_drawer]" value="1" <?php checked(!empty($options['mobile_side_drawer']), '1'); ?> />
                        فعال‌سازی منوی شناور سمت راست وسط صفحه و سایدبار کشویی موبایل
                    </label>
                </td>
            </tr>
        </table>
        <?php
    }
}
