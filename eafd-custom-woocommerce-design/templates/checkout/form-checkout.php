<?php
/**
 * Custom WooCommerce Checkout Page Template - Combined Three Styles
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

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">
    <div class="checkout-container">

        <!-- بخش فرم اطلاعات (نئومورفیسم + شیشه‌ای) -->
        <div class="glass-card neumor-card">
            <h2>جزئیات صورتحساب</h2>
            <?php if ($checkout->get_checkout_fields()) : ?>
                <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                <div id="customer_details">
                    <?php do_action('woocommerce_checkout_billing'); ?>
                    <?php do_action('woocommerce_checkout_shipping'); ?>
                </div>

                <?php do_action('woocommerce_checkout_after_customer_details'); ?>
            <?php endif; ?>
        </div>

        <!-- خلاصه سفارش و پرداخت (گلسمورفیسم + اسکئومورفیسم) -->
        <div class="glass-card order-summary">
            <h2>خلاصه سفارش و پرداخت</h2>
            <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

            <?php do_action('woocommerce_checkout_before_order_review'); ?>

            <div id="order_review" class="woocommerce-checkout-review-order">
                <?php do_action('woocommerce_checkout_order_review'); ?>
            </div>

            <?php do_action('woocommerce_checkout_after_order_review'); ?>
        </div>

        <!-- روش پرداخت کارت بانکی نمادین (اسکئومورفیسم) -->
        <div class="glass-card payment-methods">
            <h3>روش پرداخت</h3>
            <div class="skeuo-card">
                <div class="card-chip"></div>
                <div class="card-number">•••• •••• •••• ۶۰۳۷</div>
                <div class="card-holder">درگاه اتصال مستقیم شتاب</div>
                <div class="card-brand">SHETAB</div>
            </div>
            <p style="font-size: 13px; color: var(--text-secondary, #5d6d7e); margin-top: 10px;">
                <i class="fas fa-lock" style="color: var(--turquoise, #1abc9c);"></i> تمامی تراکنش‌های مالی با پروتکل‌های امن SSL و استانداردهای شاپرک رمزنگاری می‌شوند.
            </p>
        </div>

    </div>
</form>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
