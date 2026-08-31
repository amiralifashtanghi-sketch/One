<?php
/**
 * Custom WooCommerce Checkout Page Template
 */
if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_before_checkout_form', $checkout);

if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('برای تسویه‌حساب باید وارد شوید.', 'woocommerce')));
    return;
}
?>

<div class="eafd-wc-container" style="max-width: 1200px; margin: 30px auto;">
    <h2 style="font-size: 24px; font-weight: 800; color: var(--blue-primary); margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-credit-card" style="color: var(--turquoise);"></i> تکمیل خرید و تسویه‌حساب
    </h2>

    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">
        <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 24px;">

            <!-- Customer Details Forms -->
            <div>
                <?php if ($checkout->get_checkout_fields()) : ?>
                    <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                    <div class="eafd-neo-card" style="margin-bottom: 24px;">
                        <?php do_action('woocommerce_checkout_billing'); ?>
                    </div>

                    <div class="eafd-neo-card">
                        <?php do_action('woocommerce_checkout_shipping'); ?>
                    </div>

                    <?php do_action('woocommerce_checkout_after_customer_details'); ?>
                <?php endif; ?>
            </div>

            <!-- Order Review & Payment -->
            <div>
                <div class="eafd-neo-card" style="position: sticky; top: 20px;">
                    <h3 id="order_review_heading" style="font-size: 18px; font-weight: 800; color: var(--blue-primary); border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 12px; margin-bottom: 15px;">
                        <?php esc_html_e('خلاصه سفارش شما', 'woocommerce'); ?>
                    </h3>

                    <?php do_action('woocommerce_checkout_before_order_review'); ?>

                    <div id="order_review" class="woocommerce-checkout-review-order">
                        <?php do_action('woocommerce_checkout_order_review'); ?>
                    </div>

                    <?php do_action('woocommerce_checkout_after_order_review'); ?>
                </div>
            </div>

        </div>
    </form>
</div>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
