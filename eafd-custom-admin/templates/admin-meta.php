<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$message = '';

if ( isset( $_POST['eafd_save_meta_settings_nonce'] ) && wp_verify_nonce( $_POST['eafd_save_meta_settings_nonce'], 'eafd_save_meta_settings' ) ) {
    $hide_seo = isset( $_POST['hide_seo_box'] ) ? 1 : 0;
    $hide_custom_fields = isset( $_POST['hide_custom_fields'] ) ? 1 : 0;
    $hide_slug_box = isset( $_POST['hide_slug_box'] ) ? 1 : 0;
    $hide_author_box = isset( $_POST['hide_author_box'] ) ? 1 : 0;
    $custom_selectors = sanitize_textarea_field( $_POST['custom_css_selectors'] );

    update_option( 'eafd_meta_hide_seo', $hide_seo );
    update_option( 'eafd_meta_hide_custom_fields', $hide_custom_fields );
    update_option( 'eafd_meta_hide_slug', $hide_slug_box );
    update_option( 'eafd_meta_hide_author', $hide_author_box );
    update_option( 'eafd_meta_custom_css_selectors', $custom_selectors );

    $message = 'تنظیمات کنترل ریز صفحات با موفقیت ذخیره شد.';
}

$hide_seo = get_option( 'eafd_meta_hide_seo', 0 );
$hide_custom_fields = get_option( 'eafd_meta_hide_custom_fields', 0 );
$hide_slug = get_option( 'eafd_meta_hide_slug', 0 );
$hide_author = get_option( 'eafd_meta_hide_author', 0 );
$custom_selectors = get_option( 'eafd_meta_custom_css_selectors', '' );
?>

<div class="wrap eafd-admin-wrap" style="direction: rtl; font-family: Vazirmatn, sans-serif;">
    <h1 style="margin-bottom: 20px; font-weight: 700; color: #1d2327;">کنترل ریزِ جزئیات صفحات (متاباکس‌ها و عناصر CSS)</h1>

    <?php if ( ! empty( $message ) ) : ?>
        <div class="notice notice-success is-dismissible"><p><?php echo esc_html( $message ); ?></p></div>
    <?php endif; ?>

    <form method="post" action="">
        <?php wp_nonce_field( 'eafd_save_meta_settings', 'eafd_save_meta_settings_nonce' ); ?>

        <div style="background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; max-width: 800px;">
            <h2 style="font-size: 17px; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                🎯 مخفی‌سازی بخش‌های پیش‌فرض در صفحات ویرایش (برای اپراتورها):
            </h2>

            <div style="display: flex; flex-direction: column; gap: 15px;">
                <label style="font-size: 15px; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="hide_seo_box" value="1" <?php checked( $hide_seo, 1 ); ?> style="transform: scale(1.2);">
                    <span>مخفی کردن باکس سئو (Yoast SEO / Rank Math)</span>
                </label>

                <label style="font-size: 15px; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="hide_custom_fields" value="1" <?php checked( $hide_custom_fields, 1 ); ?> style="transform: scale(1.2);">
                    <span>مخفی کردن زمینه های دلخواه (Custom Fields)</span>
                </label>

                <label style="font-size: 15px; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="hide_slug_box" value="1" <?php checked( $hide_slug, 1 ); ?> style="transform: scale(1.2);">
                    <span>مخفی کردن باکس نامک / نام کوتاه (Slug)</span>
                </label>

                <label style="font-size: 15px; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="hide_author_box" value="1" <?php checked( $hide_author, 1 ); ?> style="transform: scale(1.2);">
                    <span>مخفی کردن باکس نویسنده (Author Box)</span>
                </label>
            </div>

            <hr style="margin: 25px 0; border: none; border-top: 1px solid #e2e8f0;">

            <h2 style="font-size: 17px; margin-top: 0; margin-bottom: 10px; color: #1e293b;">
                🔍 مخفی‌سازی دلخواه با کلاس یا آیدی CSS:
            </h2>
            <p style="color: #64748b; font-size: 13px; margin-bottom: 15px;">
                کلاس‌ها یا آیدی‌های CSS بخش‌هایی که می‌خواهید برای اپراتور مخفی شوند را با ویرگول (,) جدا کنید. مثال: <code>#wp-admin-bar-seo, .product-addon-field, #elementor-switch-mode-wrapper</code>
            </p>

            <textarea name="custom_css_selectors" rows="4" style="width: 100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 12px; font-family: monospace; direction: ltr;" placeholder="#postcustom, .yoast-settings-box"><?php echo esc_textarea( $custom_selectors ); ?></textarea>

            <p style="margin-top: 25px;">
                <input type="submit" class="button button-primary button-large" style="border-radius: 20px; padding: 8px 30px; height: auto; font-size: 15px;" value="💾 ذخیره تنظیمات">
            </p>
        </div>
    </form>
</div>
