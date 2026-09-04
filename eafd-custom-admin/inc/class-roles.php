<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class EAFD_Custom_Admin_Roles {

    const OPERATOR_ROLE = 'eafd_operator';

    public static function add_roles() {
        $caps = array(
            'read'                       => true,
            'read_private_posts'         => true,
            'read_private_pages'         => true,
            'edit_posts'                 => true,
            'edit_others_posts'          => true,
            'edit_published_posts'       => true,
            'publish_posts'              => true,
            'edit_pages'                 => true,
            'edit_others_pages'          => true,
            'edit_published_pages'       => true,
            'publish_pages'              => true,
            'upload_files'               => true,
            'manage_woocommerce'         => true,
            'edit_products'              => true,
            'edit_others_products'       => true,
            'edit_published_products'    => true,
            'publish_products'           => true,
            'read_private_products'      => true,
            'edit_shop_orders'           => true,
            'edit_others_shop_orders'    => true,
            'read_private_shop_orders'   => true,
        );

        add_role(
            self::OPERATOR_ROLE,
            'اپراتور پنل مدیریت (eafd)',
            $caps
        );
    }
}
