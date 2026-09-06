<?php
/**
 * Custom WooCommerce Checkout Page Template - Combined Three Styles
 * Enhanced with Accessibility (WCAG 2.1 AAA) and Agentic Microdata
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

<main class="eafd-wc-checkout-wrapper" role="main" aria-label="صفحه تسویه‌حساب سفارش">
    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data" aria-label="فرم تسویه‌حساب">
        <div class="checkout-container">

            <!-- بخش فرم اطلاعات (نئومورفیسم + شیشه‌ای) -->
            <section class="glass-card neumor-card" aria-label="اطلاعات صورت‌حساب و ارسال">
                <h1 style="font-size: 20px; font-weight: 800; color: var(--blue-primary, #0d1b2a); margin-bottom: 20px;">جزئیات صورتحساب و مشخصات خریدار</h1>
                <?php if ($checkout->get_checkout_fields()) : ?>
                    <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                    <div id="customer_details">
                        <?php do_action('woocommerce_checkout_billing'); ?>
                        <?php do_action('woocommerce_checkout_shipping'); ?>
                    </div>

                    <?php do_action('woocommerce_checkout_after_customer_details'); ?>
                <?php endif; ?>
            </section>

            <!-- خلاصه سفارش و پرداخت (گلسمورفیسم + اسکئومورفیسم) -->
            <aside class="glass-card order-summary" aria-label="بازبینی سفارش و روش‌های پرداخت">
                <h2 style="font-size: 20px; font-weight: 800; color: var(--blue-primary, #0d1b2a); margin-bottom: 20px;">خلاصه سفارش و پرداخت</h2>
                <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

                <?php do_action('woocommerce_checkout_before_order_review'); ?>

                <div id="order_review" class="woocommerce-checkout-review-order" aria-live="polite">
                    <?php do_action('woocommerce_checkout_order_review'); ?>
                </div>

                <?php do_action('woocommerce_checkout_after_order_review'); ?>
            </aside>

        </div>
    </form>
</main>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
