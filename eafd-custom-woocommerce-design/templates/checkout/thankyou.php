<?php
/**
 * Custom WooCommerce Thank You / Order Received Template
 */
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="eafd-wc-container" style="max-width: 800px; margin: 40px auto; text-align: center;">
    <div class="eafd-neo-card" style="padding: 40px 20px;">
        <?php
        if ($order) :
            do_action('woocommerce_before_thankyou', $order->get_id());
            ?>

            <?php if ($order->has_status('failed')) : ?>
                <div style="width: 70px; height: 70px; border-radius: 50%; background: rgba(231, 76, 60, 0.15); color: #e74c3c; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 20px;">
                    <i class="fas fa-times"></i>
                </div>
                <h2 style="font-weight: 800; color: #e74c3c; margin-bottom: 10px;">متأسفانه پرداخت انجام نشد!</h2>
                <p style="color: #7f8c8d; margin-bottom: 20px;"><?php esc_html_e('متأسفانه سفارش شما پردازش نشد چون بانک مرجع پرداخت شما را رد کرده است. لطفاً دوباره تلاش کنید.', 'woocommerce'); ?></p>
                <a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="eafd-btn-skeuo"><?php esc_html_e('پرداخت مجدد', 'woocommerce'); ?></a>

            <?php else : ?>
                <div style="width: 70px; height: 70px; border-radius: 50%; background: rgba(26, 188, 156, 0.15); color: var(--turquoise); display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 20px;">
                    <i class="fas fa-check"></i>
                </div>
                <h2 style="font-weight: 800; color: var(--blue-primary); margin-bottom: 10px;">با تشکر، سفارش شما با موفقیت ثبت شد!</h2>
                <p style="color: #7f8c8d; margin-bottom: 25px;">کد پیگیری سفارش شما: <strong>#<?php echo esc_html($order->get_order_number()); ?></strong></p>

                <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
                    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="eafd-btn-skeuo">
                        <i class="fas fa-user"></i> مشاهده حساب کاربری
                    </a>
                </div>

            <?php endif; ?>

            <?php do_action('woocommerce_thankyou', $order->get_id()); ?>

        <?php else : ?>
            <p><?php echo esc_html(apply_filters('woocommerce_thankyou_order_received_text', __('متشکریم. سفارش شما دریافت شد.', 'woocommerce'), null)); ?></p>
        <?php endif; ?>
    </div>
</div>
