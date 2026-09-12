<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$message = '';
$selected_operator_id = isset( $_REQUEST['operator_id'] ) ? intval( $_REQUEST['operator_id'] ) : 0;

$operators = get_users( array( 'role' => 'eafd_operator' ) );
if ( empty( $selected_operator_id ) && ! empty( $operators ) ) {
    $selected_operator_id = $operators[0]->ID;
}

// Handle Save Permissions
if ( isset( $_POST['eafd_save_permissions_nonce'] ) && wp_verify_nonce( $_POST['eafd_save_permissions_nonce'], 'eafd_save_permissions' ) ) {
    $operator_id = intval( $_POST['operator_id'] );
    $allowed_menus = isset( $_POST['allowed_menus'] ) && is_array( $_POST['allowed_menus'] ) ? array_map( 'sanitize_text_field', $_POST['allowed_menus'] ) : array();

    update_user_meta( $operator_id, 'eafd_allowed_menus', $allowed_menus );
    $selected_operator_id = $operator_id;
    $message = 'سطح دسترسی‌های اپراتور با موفقیت به‌روزرسانی شد.';
}

$all_menus = EAFD_Custom_Admin_Access_Control::get_all_registered_menus();
$user_allowed = $selected_operator_id ? get_user_meta( $selected_operator_id, 'eafd_allowed_menus', true ) : array();
if ( ! is_array( $user_allowed ) ) {
    $user_allowed = array();
}
?>

<div class="wrap eafd-admin-wrap" style="direction: rtl; font-family: Vazirmatn, sans-serif;">
    <h1 style="margin-bottom: 20px; font-weight: 700; color: #1d2327;">تعیین سطح دسترسی منوها و زیرمنوها</h1>

    <?php if ( ! empty( $message ) ) : ?>
        <div class="notice notice-success is-dismissible"><p><?php echo esc_html( $message ); ?></p></div>
    <?php endif; ?>

    <!-- Select Operator Form -->
    <div style="background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 20px; border: 1px solid #e2e8f0;">
        <form method="get" action="">
            <input type="hidden" name="page" value="eafd-custom-admin-permissions">
            <label style="font-weight: 600; font-size: 15px; margin-left: 10px;">انتخاب اپراتور:</label>
            <select name="operator_id" onchange="this.form.submit()" style="padding: 8px 15px; border-radius: 8px; border: 1px solid #cbd5e1; min-width: 250px;">
                <?php if ( empty( $operators ) ) : ?>
                    <option value="0">هیچ اپراتوری ثبت نشده است</option>
                <?php else : ?>
                    <?php foreach ( $operators as $operator ) : ?>
                        <option value="<?php echo esc_attr( $operator->ID ); ?>" <?php selected( $selected_operator_id, $operator->ID ); ?>>
                            <?php echo esc_html( $operator->display_name ); ?> (<?php echo esc_html( $operator->user_login ); ?>)
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </form>
    </div>

    <?php if ( $selected_operator_id ) : ?>
        <form method="post" action="">
            <?php wp_nonce_field( 'eafd_save_permissions', 'eafd_save_permissions_nonce' ); ?>
            <input type="hidden" name="operator_id" value="<?php echo esc_attr( $selected_operator_id ); ?>">

            <div style="background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                <h2 style="font-size: 17px; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                    📌 لیست منوها و زیرمنوها (تیک بزنید تا برای اپراتور فعال شود):
                </h2>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                    <?php foreach ( $all_menus as $menu_item ) : ?>
                        <?php
                        $is_menu_checked = in_array( $menu_item['slug'], $user_allowed, true );
                        ?>
                        <div style="border: 1px solid #e2e8f0; border-radius: 10px; padding: 15px; background: #f8fafc;">
                            <label style="font-weight: 700; font-size: 15px; display: flex; align-items: center; gap: 8px; cursor: pointer; color: #1e293b;">
                                <input type="checkbox" name="allowed_menus[]" value="<?php echo esc_attr( $menu_item['slug'] ); ?>" <?php checked( $is_menu_checked ); ?> style="transform: scale(1.2);">
                                <span><?php echo esc_html( $menu_item['title'] ); ?></span>
                            </label>

                            <?php if ( ! empty( $menu_item['submenus'] ) ) : ?>
                                <div style="margin-top: 12px; margin-right: 24px; display: flex; flex-direction: column; gap: 8px; border-right: 2px solid #cbd5e1; padding-right: 12px;">
                                    <?php foreach ( $menu_item['submenus'] as $sub ) : ?>
                                        <?php
                                        $sub_value = $menu_item['slug'] . '::' . $sub['slug'];
                                        $is_sub_checked = in_array( $sub['slug'], $user_allowed, true ) || in_array( $sub_value, $user_allowed, true );
                                        ?>
                                        <label style="font-size: 13px; color: #475569; display: flex; align-items: center; gap: 6px; cursor: pointer;">
                                            <input type="checkbox" name="allowed_menus[]" value="<?php echo esc_attr( $sub_value ); ?>" <?php checked( $is_sub_checked ); ?>>
                                            <span><?php echo esc_html( $sub['title'] ); ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <p style="margin-top: 25px;">
                    <input type="submit" class="button button-primary button-large" style="border-radius: 20px; padding: 8px 30px; height: auto; font-size: 15px;" value="💾 ذخیره دسترسی‌ها">
                </p>
            </div>
        </form>
    <?php endif; ?>
</div>
