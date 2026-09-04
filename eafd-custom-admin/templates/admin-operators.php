<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Handle Form Submission
$message = '';
$error = '';

if ( isset( $_POST['eafd_add_operator_nonce'] ) && wp_verify_nonce( $_POST['eafd_add_operator_nonce'], 'eafd_add_operator' ) ) {
    $phone = sanitize_text_field( $_POST['operator_phone'] );
    $password = $_POST['operator_password'] ?? ''; // Preserve password special characters
    $display_name = sanitize_text_field( $_POST['operator_name'] );

    if ( empty( $phone ) || empty( $password ) ) {
        $error = 'لطفاً شماره موبایل و رمز عبور را وارد نمایید.';
    } elseif ( username_exists( $phone ) ) {
        $error = 'این شماره موبایل قبلاً به عنوان نام کاربری ثبت شده است.';
    } else {
        $user_id = wp_create_user( $phone, $password, $phone . '@eafd.local' );
        if ( is_wp_error( $user_id ) ) {
            $error = $user_id->get_error_message();
        } else {
            $user = new WP_User( $user_id );
            $user->set_role( 'eafd_operator' );
            if ( ! empty( $display_name ) ) {
                wp_update_user( array(
                    'ID' => $user_id,
                    'display_name' => $display_name,
                    'first_name' => $display_name
                ) );
            }
            update_user_meta( $user_id, 'eafd_phone_number', $phone );
            $message = 'اپراتور جدید با موفقیت ایجاد شد.';
        }
    }
}

// Delete Operator securely
if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['user_id'] ) && check_admin_referer( 'eafd_delete_operator_' . $_GET['user_id'] ) ) {
    $user_id = intval( $_GET['user_id'] );
    $target_user = get_userdata( $user_id );

    if ( current_user_can( 'delete_users' ) && get_current_user_id() !== $user_id && $target_user && in_array( 'eafd_operator', (array) $target_user->roles, true ) ) {
        wp_delete_user( $user_id );
        $message = 'اپراتور با موفقیت حذف شد.';
    } else {
        $error = 'امکان حذف این کاربر وجود ندارد.';
    }
}

// Get all operators
$operators = get_users( array(
    'role' => 'eafd_operator'
) );
?>

<div class="wrap eafd-admin-wrap" style="direction: rtl; font-family: Vazirmatn, sans-serif;">
    <h1 style="margin-bottom: 20px; font-weight: 700; color: #1d2327;">مدیریت اپراتورها و ساخت ورود اختصاصی</h1>

    <?php if ( ! empty( $message ) ) : ?>
        <div class="notice notice-success is-dismissible"><p><?php echo esc_html( $message ); ?></p></div>
    <?php endif; ?>
    <?php if ( ! empty( $error ) ) : ?>
        <div class="notice notice-error is-dismissible"><p><?php echo esc_html( $error ); ?></p></div>
    <?php endif; ?>

    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
        <!-- Add Operator Card -->
        <div style="flex: 1; min-width: 300px; background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
            <h2 style="font-size: 18px; margin-top: 0; margin-bottom: 15px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">➕ ساخت اپراتور جدید</h2>
            <form method="post" action="">
                <?php wp_nonce_field( 'eafd_add_operator', 'eafd_add_operator_nonce' ); ?>

                <p>
                    <label style="display: block; font-weight: 600; margin-bottom: 5px;">نام و نام خانوادگی (اختیاری):</label>
                    <input type="text" name="operator_name" class="regular-text" style="width: 100%; border-radius: 8px; padding: 8px;" placeholder="مثال: علی محمدی">
                </p>

                <p>
                    <label style="display: block; font-weight: 600; margin-bottom: 5px;">شماره موبایل (نام کاربری ورود):</label>
                    <input type="text" name="operator_phone" class="regular-text" style="width: 100%; border-radius: 8px; padding: 8px;" placeholder="مثال: 09123456789" required>
                </p>

                <p>
                    <label style="display: block; font-weight: 600; margin-bottom: 5px;">رمز ورود:</label>
                    <input type="password" name="operator_password" class="regular-text" style="width: 100%; border-radius: 8px; padding: 8px;" placeholder="رمز عبور قوی" required>
                </p>

                <p style="margin-top: 20px;">
                    <input type="submit" class="button button-primary" style="border-radius: 20px; padding: 6px 20px; height: auto; font-size: 14px;" value="ایجاد اپراتور">
                </p>
            </form>
        </div>

        <!-- Operator List Card -->
        <div style="flex: 2; min-width: 320px; background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
            <h2 style="font-size: 18px; margin-top: 0; margin-bottom: 15px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">👥 لیست اپراتورهای ساخته شده</h2>

            <table class="wp-list-table widefat fixed striped" style="border-radius: 8px; overflow: hidden; border: none;">
                <thead>
                    <tr>
                        <th style="padding: 12px;">نام اپراتور</th>
                        <th style="padding: 12px;">شماره موبایل</th>
                        <th style="padding: 12px;">عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( empty( $operators ) ) : ?>
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 20px; color: #64748b;">هیچ اپراتوری ثبت نشده است.</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ( $operators as $operator ) : ?>
                            <tr>
                                <td style="padding: 12px; vertical-align: middle;">
                                    <strong><?php echo esc_html( $operator->display_name ); ?></strong>
                                </td>
                                <td style="padding: 12px; vertical-align: middle;">
                                    <code><?php echo esc_html( $operator->user_login ); ?></code>
                                </td>
                                <td style="padding: 12px; vertical-align: middle;">
                                    <?php
                                    $delete_url = wp_nonce_url(
                                        add_query_arg( array(
                                            'page' => 'eafd-custom-admin',
                                            'action' => 'delete',
                                            'user_id' => $operator->ID
                                        ), admin_url( 'admin.php' ) ),
                                        'eafd_delete_operator_' . $operator->ID
                                    );
                                    ?>
                                    <a href="<?php echo esc_url( $delete_url ); ?>" onclick="return confirm('آیا از حذف این اپراتور اطمینان دارید؟');" style="color: #ef4444; text-decoration: none; font-weight: 600;">🗑️ حذف</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
