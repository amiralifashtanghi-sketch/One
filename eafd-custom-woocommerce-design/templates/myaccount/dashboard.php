<?php
/**
 * Custom My Account Dashboard Template
 */
if (!defined('ABSPATH')) {
    exit;
}

$current_user = wp_get_current_user();
$options = EAFD_Admin_Settings::get_instance()->get_options();
?>

<div class="eafd-wc-container eafd-dashboard-container">
    <?php if (!empty($options['enable_floating_blobs'])): ?>
        <div class="eafd-bg-decoration"></div>
        <div class="eafd-bg-decoration"></div>
        <div class="eafd-bg-decoration"></div>
    <?php endif; ?>

    <!-- Main Content Area -->
    <div style="flex: 1; width: 100%;">
        <!-- Welcome Banner -->
        <div class="eafd-neo-card" style="background: linear-gradient(135deg, var(--blue-primary) 0%, #114b70 50%, var(--turquoise) 100%); color: #fff; margin-bottom: 24px; position: relative; overflow: hidden;">
            <div style="position: relative; z-index: 2; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h2 style="font-size: 24px; font-weight: 800; margin-bottom: 8px; color: #fff;">
                        سلام <?php echo esc_html($current_user->display_name); ?> عزیز، خوش آمدید 👋
                    </h2>
                    <p style="font-size: 14px; opacity: 0.9; margin: 0;">
                        از طریق پیشخوان حساب کاربری خود می‌توانید آخرین سفارشات، رزروها و تنظیمات حساب خود را مدیریت کنید.
                    </p>
                </div>
                <?php if (!empty($options['custom_logo_url'])): ?>
                    <img src="<?php echo esc_url($options['custom_logo_url']); ?>" alt="Logo" style="max-height: 55px; border-radius: 10px;" />
                <?php endif; ?>
            </div>
        </div>

        <!-- Widgets Grid -->
        <div class="eafd-widgets-grid">
            <?php for ($i = 1; $i <= 4; $i++): ?>
                <?php if (!empty($options['widget_' . $i . '_active'])): ?>
                    <div class="eafd-neo-card" style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 12px;">
                        <div style="width: 50px; height: 50px; border-radius: 50%; background: rgba(26, 188, 156, 0.15); display: flex; align-items: center; justify-content: center; color: var(--turquoise); font-size: 22px;">
                            <?php if (!empty($options['widget_' . $i . '_image'])): ?>
                                <img src="<?php echo esc_url($options['widget_' . $i . '_image']); ?>" style="width: 28px; height: 28px; object-fit: contain;" />
                            <?php else: ?>
                                <i class="<?php echo esc_attr($options['widget_' . $i . '_icon']); ?>"></i>
                            <?php endif; ?>
                        </div>
                        <div style="font-size: 24px; font-weight: 800; color: var(--blue-primary);">
                            <?php echo esc_html($options['widget_' . $i . '_value']); ?>
                        </div>
                        <div style="font-size: 13px; color: #7f8c8d; font-weight: 500;">
                            <?php echo esc_html($options['widget_' . $i . '_title']); ?>
                        </div>
                        <?php if (!empty($options['widget_' . $i . '_badge'])): ?>
                            <span style="background: var(--orange); color: #fff; font-size: 11px; padding: 3px 10px; border-radius: 12px; font-weight: 600;">
                                <?php echo esc_html($options['widget_' . $i . '_badge']); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endfor; ?>
        </div>

        <!-- Recent Activity Card -->
        <div class="eafd-neo-card">
            <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 16px; color: var(--blue-primary); border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 10px;">
                <i class="fas fa-clock"></i> دسترسی‌های سریع حساب
            </h3>
            <p style="font-size: 14px; color: #5d6d7e; line-height: 1.8;">
                از این قسمت می‌توانید سفارش‌های جدید خود را بررسی کرده، آدرس‌های ارسال فاکتور را ویرایش کنید یا گذرواژه خود را تغییر دهید.
            </p>
        </div>
    </div>
</div>
