<?php
/**
 * Custom My Account Orders Template
 */
if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_before_account_orders', $has_orders);
?>

<div class="eafd-wc-container">
    <h3 style="font-size: 20px; font-weight: 800; margin-bottom: 20px; color: var(--blue-primary); display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-shopping-bag" style="color: var(--turquoise);"></i> لیست سفارش‌ها و رزروها
    </h3>

    <?php if ($has_orders) : ?>
        <div style="display: flex; flex-direction: column; gap: 16px;">
            <?php
            foreach ($customer_orders->orders as $customer_order) {
                $order      = wc_get_order($customer_order);
                $item_count = $order->get_item_count();
                $status_name = wc_get_order_status_name($order->get_status());
                ?>
                <div class="eafd-neo-card" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                    <div>
                        <div style="font-weight: 700; font-size: 16px; color: var(--blue-primary); margin-bottom: 6px;">
                            سفارش #<?php echo esc_html($order->get_order_number()); ?>
                        </div>
                        <div style="font-size: 13px; color: #7f8c8d;">
                            تاریخ: <?php echo esc_html(wc_format_datetime($order->get_date_created())); ?> | تعداد: <?php echo esc_html($item_count); ?> عدد
                        </div>
                    </div>
                    <div>
                        <span style="background: rgba(26, 188, 156, 0.15); color: var(--turquoise); padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 700;">
                            <?php echo esc_html($status_name); ?>
                        </span>
                    </div>
                    <div>
                        <span style="font-weight: 800; font-size: 16px; color: var(--blue-primary); margin-left: 15px;">
                            <?php echo wp_kses_post($order->get_formatted_order_total()); ?>
                        </span>
                        <?php
                        $actions = wc_get_account_orders_actions($order);
                        if (!empty($actions)) {
                            foreach ($actions as $key => $action) {
                                echo '<a href="' . esc_url($action['url']) . '" class="eafd-btn-skeuo" style="padding: 8px 16px; font-size: 13px;">' . esc_html($action['name']) . '</a>';
                            }
                        }
                        ?>
                    </div>
                </div>
            <?php } ?>
        </div>

        <?php do_action('woocommerce_before_account_orders_pagination'); ?>

        <?php if (1 < $customer_orders->max_num_pages) : ?>
            <div style="margin-top: 20px; text-align: center;">
                <?php if (1 !== $current_page) : ?>
                    <a class="eafd-btn-skeuo" href="<?php echo esc_url(wc_get_endpoint_url('orders', $current_page - 1)); ?>"><?php esc_html_e('قبلی', 'woocommerce'); ?></a>
                <?php endif; ?>

                <?php if (intval($customer_orders->max_num_pages) !== $current_page) : ?>
                    <a class="eafd-btn-skeuo" href="<?php echo esc_url(wc_get_endpoint_url('orders', $current_page + 1)); ?>"><?php esc_html_e('بعدی', 'woocommerce'); ?></a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php else : ?>
        <div class="eafd-neo-card" style="text-align: center; padding: 40px;">
            <i class="fas fa-shopping-cart" style="font-size: 40px; color: #bdc3c7; margin-bottom: 15px;"></i>
            <p style="font-size: 15px; color: #7f8c8d;"><?php esc_html_e('هنوز هیچ سفارشی ثبت نکرده‌اید.', 'woocommerce'); ?></p>
            <a href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>" class="eafd-btn-skeuo" style="margin-top: 15px;">
                <?php esc_html_e('مشاهده محصولات و رزروها', 'woocommerce'); ?>
            </a>
        </div>
    <?php endif; ?>
</div>

<?php do_action('woocommerce_after_account_orders', $has_orders); ?>
