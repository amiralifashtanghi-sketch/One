<?php
/**
 * Custom Edit Account Form Template
 */
if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_before_edit_account_form');
?>

<div class="eafd-wc-container">
    <div class="eafd-neo-card">
        <h3 style="font-size: 20px; font-weight: 800; margin-bottom: 20px; color: var(--blue-primary); display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-user-edit" style="color: var(--turquoise);"></i> ویرایش اطلاعات حساب کاربری
        </h3>

        <form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action('woocommerce_edit_account_form_tag'); ?>>

            <?php do_action('woocommerce_edit_account_form_start'); ?>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
                    <label for="account_first_name" style="font-weight: 600; color: var(--blue-primary);"><?php esc_html_e('نام', 'woocommerce'); ?> <span class="required">*</span></label>
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr($user->first_name); ?>" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #cbd5e1; background: #fff;" />
                </p>
                <p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
                    <label for="account_last_name" style="font-weight: 600; color: var(--blue-primary);"><?php esc_html_e('نام خانوادگی', 'woocommerce'); ?> <span class="required">*</span></label>
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr($user->last_name); ?>" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #cbd5e1; background: #fff;" />
                </p>
            </div>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide" style="margin-bottom: 15px;">
                <label for="account_display_name" style="font-weight: 600; color: var(--blue-primary);"><?php esc_html_e('نام نمایشی', 'woocommerce'); ?> <span class="required">*</span></label>
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" value="<?php echo esc_attr($user->display_name); ?>" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #cbd5e1; background: #fff;" />
            </p>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide" style="margin-bottom: 20px;">
                <label for="account_email" style="font-weight: 600; color: var(--blue-primary);"><?php esc_html_e('آدرس ایمیل', 'woocommerce'); ?> <span class="required">*</span></label>
                <input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr($user->user_email); ?>" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #cbd5e1; background: #fff;" />
            </p>

            <fieldset style="border: 1px dashed rgba(26, 188, 156, 0.4); padding: 20px; border-radius: 12px; margin-bottom: 20px; background: rgba(255,255,255,0.5);">
                <legend style="font-weight: 700; color: var(--blue-primary); padding: 0 10px;"><?php esc_html_e('تغییر گذرواژه', 'woocommerce'); ?></legend>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide" style="margin-bottom: 15px;">
                    <label for="password_current" style="font-weight: 600;"><?php esc_html_e('گذرواژه فعلی (در صورت عدم تغییر خالی بگذارید)', 'woocommerce'); ?></label>
                    <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" autocomplete="off" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #cbd5e1; background: #fff;" />
                </p>
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide" style="margin-bottom: 15px;">
                    <label for="password_1" style="font-weight: 600;"><?php esc_html_e('گذرواژه جدید (در صورت عدم تغییر خالی بگذارید)', 'woocommerce'); ?></label>
                    <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" autocomplete="off" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #cbd5e1; background: #fff;" />
                </p>
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="password_2" style="font-weight: 600;"><?php esc_html_e('تکرار گذرواژه جدید', 'woocommerce'); ?></label>
                    <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" autocomplete="off" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #cbd5e1; background: #fff;" />
                </p>
            </fieldset>

            <?php do_action('woocommerce_edit_account_form'); ?>

            <p style="margin-top: 20px;">
                <?php wp_nonce_field('save_account_details', 'save-account-details-nonce'); ?>
                <button type="submit" class="woocommerce-Button button eafd-btn-skeuo" name="save_account_details" value="<?php esc_attr_e('ذخیره تغییرات', 'woocommerce'); ?>">
                    <i class="fas fa-save"></i> <?php esc_html_e('ذخیره تغییرات', 'woocommerce'); ?>
                </button>
                <input type="hidden" name="action" value="save_account_details" />
            </p>

            <?php do_action('woocommerce_edit_account_form_end'); ?>
        </form>
    </div>
</div>

<?php do_action('woocommerce_after_edit_account_form'); ?>
