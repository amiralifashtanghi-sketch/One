<?php
/**
 * Custom Downloads Account Template
 */
if (!defined('ABSPATH')) {
    exit;
}

$downloads = WC()->customer->get_downloadable_products();
$has_downloads = (bool) $downloads;

do_action('woocommerce_before_account_downloads', $has_downloads);
?>

<div class="eafd-wc-container">
    <h3 style="font-size: 20px; font-weight: 800; margin-bottom: 20px; color: var(--blue-primary); display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-download" style="color: var(--turquoise);"></i> دانلودهای شما
    </h3>

    <?php if ($has_downloads) : ?>
        <div style="display: flex; flex-direction: column; gap: 16px;">
            <?php foreach ($downloads as $download) : ?>
                <div class="eafd-neo-card" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                    <div>
                        <div style="font-weight: 700; font-size: 16px; color: var(--blue-primary); margin-bottom: 6px;">
                            <?php echo esc_html($download['download_name']); ?>
                        </div>
                        <div style="font-size: 13px; color: #7f8c8d;">
                            تعداد دانلود باقیمانده: <?php echo is_numeric($download['downloads_remaining']) ? esc_html($download['downloads_remaining']) : esc_html__('نامحدود', 'woocommerce'); ?>
                        </div>
                    </div>
                    <div>
                        <a href="<?php echo esc_url($download['download_url']); ?>" class="eafd-btn-skeuo" style="padding: 10px 20px; font-size: 13px;">
                            <i class="fas fa-cloud-download-alt"></i> دانلود فایل
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="eafd-neo-card" style="text-align: center; padding: 40px;">
            <i class="fas fa-cloud-download-alt" style="font-size: 40px; color: #bdc3c7; margin-bottom: 15px;"></i>
            <p style="font-size: 15px; color: #7f8c8d;"><?php esc_html_e('هنوز هیچ فایل قابل دانلودی وجود ندارد.', 'woocommerce'); ?></p>
        </div>
    <?php endif; ?>
</div>

<?php do_action('woocommerce_after_account_downloads', $has_downloads); ?>
