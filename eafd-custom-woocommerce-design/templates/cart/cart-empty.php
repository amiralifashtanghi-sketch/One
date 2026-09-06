<?php
/**
 * Custom WooCommerce Empty Cart Template
 */
if (!defined('ABSPATH')) {
    exit;
}

$options = EAFD_Admin_Settings::get_instance()->get_options();
?>

<div class="eafd-wc-container" style="max-width: 800px; margin: 50px auto; text-align: center; direction: rtl; font-family: 'Vazirmatn', sans-serif;">
    <?php if (!empty($options['enable_floating_blobs'])): ?>
        <div class="eafd-bg-decoration"></div>
        <div class="eafd-bg-decoration"></div>
        <div class="eafd-bg-decoration"></div>
    <?php endif; ?>

    <div class="eafd-neo-card" style="padding: 50px 20px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 20px;">
        <div style="width: 90px; height: 90px; border-radius: 50%; background: rgba(26, 188, 156, 0.15); display: flex; align-items: center; justify-content: center; color: var(--turquoise); font-size: 40px; box-shadow: var(--shadow-neo-out);">
            <i class="fas fa-shopping-basket"></i>
        </div>

        <h2 style="font-size: 22px; font-weight: 800; color: var(--blue-primary); margin: 0;">
            سبد خرید شما خالی است!
        </h2>

        <p style="font-size: 14px; color: #7f8c8d; max-width: 450px; line-height: 1.8; margin: 0;">
            شما هنوز هیچ محصول یا رزروی به سبد خرید خود اضافه نکرده‌اید. برای مشاهده محصولات و خدمات به فروشگاه مراجعه کنید.
        </p>

        <?php do_action('woocommerce_cart_is_empty'); ?>

        <a href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>" class="eafd-btn-skeuo" style="margin-top: 10px; padding: 14px 28px; font-size: 15px;">
            <i class="fas fa-store"></i> <?php esc_html_e('مشاهده و بازگشت به فروشگاه', 'woocommerce'); ?>
        </a>
    </div>
</div>
