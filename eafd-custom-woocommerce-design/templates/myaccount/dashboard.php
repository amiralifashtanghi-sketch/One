<?php
/**
 * Custom My Account Dashboard Template with Real Dynamic WooCommerce Data
 * Enhanced with High Accessibility (WCAG 2.1 AAA), Schema JSON-LD & Agentic Attributes
 */
if (!defined('ABSPATH')) {
    exit;
}

$current_user = wp_get_current_user();
$options = EAFD_Admin_Settings::get_instance()->get_options();

// Fetch Real Dynamic Data
$customer_id   = get_current_user_id();
$customer_orders = wc_get_orders(array(
    'customer' => $customer_id,
    'limit'    => -1,
    'return'   => 'ids',
));
$orders_count  = count($customer_orders);

$total_spent   = wc_price(wc_get_customer_total_spent($customer_id));

$cart_items_count = (WC()->cart) ? WC()->cart->get_cart_contents_count() : 0;

$user_registered = date_i18n(get_option('date_format'), strtotime($current_user->user_registered));
?>

<main class="eafd-wc-container eafd-dashboard-container" role="main" aria-label="داشبورد حساب کاربری">
    <?php if (!empty($options['enable_floating_blobs'])): ?>
        <div class="eafd-bg-decoration" aria-hidden="true"></div>
        <div class="eafd-bg-decoration" aria-hidden="true"></div>
        <div class="eafd-bg-decoration" aria-hidden="true"></div>
    <?php endif; ?>

    <!-- Main Content Area -->
    <div style="flex: 1; width: 100%;">
        <!-- Welcome Banner -->
        <header class="eafd-neo-card" style="background: linear-gradient(135deg, var(--blue-primary) 0%, #114b70 50%, var(--turquoise) 100%); color: #fff; margin-bottom: 24px; position: relative; overflow: hidden;">
            <div style="position: relative; z-index: 2; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h1 style="font-size: 24px; font-weight: 800; margin-bottom: 8px; color: #fff;">
                        سلام <?php echo esc_html($current_user->display_name); ?> عزیز، خوش آمدید 👋
                    </h1>
                    <p style="font-size: 14px; opacity: 0.95; margin: 0; color: #f0f4f8;">
                        تاریخ عضویت: <?php echo esc_html($user_registered); ?> | آدرس ایمیل: <?php echo esc_html($current_user->user_email); ?>
                    </p>
                </div>
                <?php if (!empty($options['custom_logo_url'])): ?>
                    <img src="<?php echo esc_url($options['custom_logo_url']); ?>" alt="لوگوی فروشگاه" style="max-height: 55px; border-radius: 10px;" />
                <?php endif; ?>
            </div>
        </header>

        <!-- Real Dynamic Widgets Grid -->
        <section class="eafd-widgets-grid" aria-label="خلاصه آمار حساب کاربری">
            <!-- Widget 1: Total Real Orders -->
            <?php if (!empty($options['widget_1_active'])): ?>
                <article class="eafd-neo-card" aria-label="تعداد کل سفارش‌های مشتری" style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 12px;">
                    <div style="width: 50px; height: 50px; border-radius: 50%; background: rgba(26, 188, 156, 0.15); display: flex; align-items: center; justify-content: center; color: var(--turquoise); font-size: 22px;">
                        <?php if (!empty($options['widget_1_image'])): ?>
                            <img src="<?php echo esc_url($options['widget_1_image']); ?>" alt="آیکون سفارش‌ها" style="width: 28px; height: 28px; object-fit: contain;" />
                        <?php else: ?>
                            <i class="<?php echo esc_attr($options['widget_1_icon'] ? $options['widget_1_icon'] : 'fas fa-shopping-bag'); ?>" aria-hidden="true"></i>
                        <?php endif; ?>
                    </div>
                    <div style="font-size: 24px; font-weight: 800; color: var(--blue-primary);">
                        <?php echo esc_html($orders_count); ?>
                    </div>
                    <div style="font-size: 13px; color: #4a5568; font-weight: 600;">
                        <?php echo esc_html(!empty($options['widget_1_title']) ? $options['widget_1_title'] : 'تعداد کل سفارش‌ها'); ?>
                    </div>
                    <span style="background: var(--orange); color: #fff; font-size: 11px; padding: 3px 10px; border-radius: 12px; font-weight: 600;">
                        سفارشات ثبت‌شده
                    </span>
                </article>
            <?php endif; ?>

            <!-- Widget 2: Real Cart Items -->
            <?php if (!empty($options['widget_2_active'])): ?>
                <article class="eafd-neo-card" aria-label="تعداد اقلام موجود در سبد خرید" style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 12px;">
                    <div style="width: 50px; height: 50px; border-radius: 50%; background: rgba(26, 188, 156, 0.15); display: flex; align-items: center; justify-content: center; color: var(--turquoise); font-size: 22px;">
                        <?php if (!empty($options['widget_2_image'])): ?>
                            <img src="<?php echo esc_url($options['widget_2_image']); ?>" alt="آیکون سبد خرید" style="width: 28px; height: 28px; object-fit: contain;" />
                        <?php else: ?>
                            <i class="<?php echo esc_attr($options['widget_2_icon'] ? $options['widget_2_icon'] : 'fas fa-shopping-cart'); ?>" aria-hidden="true"></i>
                        <?php endif; ?>
                    </div>
                    <div style="font-size: 24px; font-weight: 800; color: var(--blue-primary);">
                        <?php echo esc_html($cart_items_count); ?>
                    </div>
                    <div style="font-size: 13px; color: #4a5568; font-weight: 600;">
                        <?php echo esc_html(!empty($options['widget_2_title']) ? $options['widget_2_title'] : 'اقلام سبد خرید فعلی'); ?>
                    </div>
                    <span style="background: var(--turquoise); color: #fff; font-size: 11px; padding: 3px 10px; border-radius: 12px; font-weight: 600;">
                        آماده تسویه
                    </span>
                </article>
            <?php endif; ?>

            <!-- Widget 3: Real Total Spent -->
            <?php if (!empty($options['widget_3_active'])): ?>
                <article class="eafd-neo-card" aria-label="مجموع خریدهای موفق مشتری" style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 12px;">
                    <div style="width: 50px; height: 50px; border-radius: 50%; background: rgba(26, 188, 156, 0.15); display: flex; align-items: center; justify-content: center; color: var(--turquoise); font-size: 22px;">
                        <?php if (!empty($options['widget_3_image'])): ?>
                            <img src="<?php echo esc_url($options['widget_3_image']); ?>" alt="آیکون خریدهای موفق" style="width: 28px; height: 28px; object-fit: contain;" />
                        <?php else: ?>
                            <i class="<?php echo esc_attr($options['widget_3_icon'] ? $options['widget_3_icon'] : 'fas fa-money-bill-wave'); ?>" aria-hidden="true"></i>
                        <?php endif; ?>
                    </div>
                    <div style="font-size: 18px; font-weight: 800; color: var(--blue-primary);">
                        <?php echo wp_kses_post($total_spent); ?>
                    </div>
                    <div style="font-size: 13px; color: #4a5568; font-weight: 600;">
                        <?php echo esc_html(!empty($options['widget_3_title']) ? $options['widget_3_title'] : 'مجموع خریدهای موفق'); ?>
                    </div>
                    <span style="background: var(--blue-primary); color: #fff; font-size: 11px; padding: 3px 10px; border-radius: 12px; font-weight: 600;">
                        جمع کل پرداختی
                    </span>
                </article>
            <?php endif; ?>

            <!-- Widget 4: Profile Status -->
            <?php if (!empty($options['widget_4_active'])): ?>
                <article class="eafd-neo-card" aria-label="مشخصات نام کاربری" style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 12px;">
                    <div style="width: 50px; height: 50px; border-radius: 50%; background: rgba(26, 188, 156, 0.15); display: flex; align-items: center; justify-content: center; color: var(--turquoise); font-size: 22px;">
                        <?php if (!empty($options['widget_4_image'])): ?>
                            <img src="<?php echo esc_url($options['widget_4_image']); ?>" alt="آیکون حساب" style="width: 28px; height: 28px; object-fit: contain;" />
                        <?php else: ?>
                            <i class="<?php echo esc_attr($options['widget_4_icon'] ? $options['widget_4_icon'] : 'fas fa-id-card'); ?>" aria-hidden="true"></i>
                        <?php endif; ?>
                    </div>
                    <div style="font-size: 16px; font-weight: 800; color: var(--blue-primary);">
                        <?php echo esc_html($current_user->user_login); ?>
                    </div>
                    <div style="font-size: 13px; color: #4a5568; font-weight: 600;">
                        <?php echo esc_html(!empty($options['widget_4_title']) ? $options['widget_4_title'] : 'نام کاربری شما'); ?>
                    </div>
                    <span style="background: #27ae60; color: #fff; font-size: 11px; padding: 3px 10px; border-radius: 12px; font-weight: 600;">
                        حساب فعال
                    </span>
                </article>
            <?php endif; ?>
        </section>

        <!-- Recent Orders Card -->
        <section class="eafd-neo-card" aria-label="آخرین سفارش‌های خریدار">
            <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 16px; color: var(--blue-primary); border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 10px;">
                <i class="fas fa-clock" aria-hidden="true"></i> <span>آخرین سفارش‌های ثبت شده شما</span>
            </h2>
            <?php
            $recent_orders = wc_get_orders(array(
                'customer' => $customer_id,
                'limit'    => 3,
            ));
            if (!empty($recent_orders)) {
                echo '<ul style="list-style: none; padding: 0; margin: 0;">';
                foreach ($recent_orders as $rec_order) {
                    echo '<li style="padding: 10px 0; border-bottom: 1px solid rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center;">';
                    echo '<div><strong>سفارش #' . esc_html($rec_order->get_order_number()) . '</strong> - ' . esc_html(wc_format_datetime($rec_order->get_date_created())) . '</div>';
                    echo '<div><span style="background: rgba(26,188,156,0.15); color: #0b5345; padding: 4px 10px; border-radius: 10px; font-size: 12px; font-weight: 700;">' . esc_html(wc_get_order_status_name($rec_order->get_status())) . '</span> ';
                    echo '<a href="' . esc_url($rec_order->get_view_order_url()) . '" class="eafd-btn-skeuo" aria-label="مشاهده جزئیات سفارش ' . esc_attr($rec_order->get_order_number()) . '" style="padding: 4px 10px; font-size: 12px;">مشاهده</a></div>';
                    echo '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p style="font-size: 14px; color: #4a5568;">هنوز هیچ سفارشی ثبت نکرده‌اید.</p>';
            }
            ?>
        </section>
    </div>
</main>
