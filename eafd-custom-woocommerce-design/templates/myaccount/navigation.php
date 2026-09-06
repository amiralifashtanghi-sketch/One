<?php
/**
 * Custom My Account Navigation Template - Glassmorphism + Neomorphism
 * Enhanced with High Accessibility (WCAG 2.1 AAA) & ARIA attributes
 */
if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_before_account_navigation');
?>

<nav class="woocommerce-MyAccount-navigation glass-card eafd-sidebar" role="navigation" aria-label="منوی حساب کاربری" style="background: var(--glass-bg, rgba(255, 255, 255, 0.7)); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.8); border-radius: 20px; padding: 20px 16px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.06); margin-bottom: 24px; direction: rtl;">
    <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px;">
        <?php foreach (wc_get_account_menu_items() as $endpoint => $label) :
            $icon = 'fa-link';
            switch($endpoint) {
                case 'dashboard': $icon = 'fa-tachometer-alt'; break;
                case 'orders': $icon = 'fa-shopping-bag'; break;
                case 'downloads': $icon = 'fa-file-download'; break;
                case 'edit-address': $icon = 'fa-map-marker-alt'; break;
                case 'edit-account': $icon = 'fa-user-edit'; break;
                case 'customer-logout': $icon = 'fa-sign-out-alt'; break;
            }
            $is_active = is_wc_endpoint_url($endpoint) || ('dashboard' === $endpoint && is_account_page() && !WC()->query->get_current_endpoint());
            $active_attr = $is_active ? 'aria-current="page"' : '';
        ?>
            <li class="<?php echo wc_get_account_menu_item_classes($endpoint); ?>" style="margin: 0;">
                <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>" <?php echo $active_attr; ?> style="display: flex; align-items: center; gap: 12px; padding: 12px 18px; border-radius: 14px; font-weight: 700; color: var(--blue-primary, #1a5276); text-decoration: none; transition: all 0.3s ease; background: rgba(255,255,255,0.4); border: 1px solid rgba(255,255,255,0.6);">
                    <i class="fas <?php echo esc_attr($icon); ?>" aria-hidden="true" style="color: var(--turquoise, #1abc9c); font-size: 16px; width: 20px; text-align: center;"></i>
                    <span><?php echo esc_html($label); ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<?php do_action('woocommerce_after_account_navigation'); ?>
