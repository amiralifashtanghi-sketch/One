<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class EAFD_Custom_Admin_Access_Control {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'filter_admin_menus' ), 99999 );
        add_action( 'admin_init', array( $this, 'redirect_and_enforce_page_access' ) );
    }

    /**
     * Redirect operators accessing standard wp-admin to /admin unless in iframe, and enforce page access
     */
    public function redirect_and_enforce_page_access() {
        if ( wp_doing_ajax() || wp_doing_cron() ) {
            return;
        }

        if ( current_user_can( 'administrator' ) ) {
            return;
        }

        $user_id = get_current_user_id();
        $user = get_userdata( $user_id );
        if ( ! $user || ! in_array( 'eafd_operator', (array) $user->roles, true ) ) {
            return;
        }

        $is_iframe = isset( $_REQUEST['eafd_iframe'] ) && $_REQUEST['eafd_iframe'] == 1;
        $has_referer_iframe = ! empty( $_SERVER['HTTP_REFERER'] ) && strpos( $_SERVER['HTTP_REFERER'], 'eafd_iframe=1' ) !== false;

        // If accessed directly outside /admin frame, redirect to /admin with target page parameter
        if ( ! $is_iframe && ! $has_referer_iframe && is_admin() && strpos( $_SERVER['REQUEST_URI'] ?? '', 'eafd_iframe' ) === false ) {
            wp_redirect( home_url( '/admin' ) );
            exit;
        }

        // Enforce page access for operators inside iframe
        $allowed = self::get_allowed_menus_for_user( $user_id );
        if ( $allowed === 'all' ) {
            return;
        }

        global $pagenow;
        $current_page = $pagenow;
        $page_arg = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';

        // Allow profile, logout, dashboard home
        if ( in_array( $current_page, array( 'profile.php', 'index.php' ), true ) && empty( $page_arg ) ) {
            return;
        }

        $target_slug = ! empty( $page_arg ) ? $page_arg : $current_page;

        $is_permitted = false;
        if ( in_array( $target_slug, $allowed, true ) || in_array( $current_page, $allowed, true ) ) {
            $is_permitted = true;
        } else {
            foreach ( $allowed as $allowed_item ) {
                if ( strpos( $allowed_item, '::' ) !== false ) {
                    list( $parent, $child ) = explode( '::', $allowed_item, 2 );
                    if ( $child === $target_slug || $child === $current_page || $parent === $current_page ) {
                        $is_permitted = true;
                        break;
                    }
                }
            }
        }

        if ( ! $is_permitted ) {
            wp_die( 'شما اجازه دسترسی به این بخش را ندارید.', 'دسترسی محدود شده', array( 'response' => 403 ) );
        }
    }

    /**
     * Get all active WordPress menus and submenus
     */
    public static function get_all_registered_menus() {
        global $menu, $submenu;

        // Safely load admin environment without breaking frontend execution
        if ( empty( $menu ) || ! is_array( $menu ) || count( $menu ) < 3 ) {
            if ( ! function_exists( 'get_admin_page_title' ) && file_exists( ABSPATH . 'wp-admin/includes/admin.php' ) ) {
                @require_once ABSPATH . 'wp-admin/includes/admin.php';
            }

            // Load WooCommerce admin dependencies safely if present
            if ( class_exists( 'WooCommerce' ) && defined( 'WC_ABSPATH' ) ) {
                if ( ! function_exists( 'wc_get_page_screen_id' ) && file_exists( WC_ABSPATH . 'includes/admin/wc-admin-functions.php' ) ) {
                    @require_once WC_ABSPATH . 'includes/admin/wc-admin-functions.php';
                }
            }

            if ( ! function_exists( 'wc_get_page_screen_id' ) ) {
                function wc_get_page_screen_id() { return ''; }
            }

            if ( file_exists( ABSPATH . 'wp-admin/menu.php' ) ) {
                @include_once ABSPATH . 'wp-admin/menu.php';
            }
        }

        $all_menus = array();

        if ( ! empty( $menu ) && is_array( $menu ) ) {
            foreach ( $menu as $item ) {
                if ( empty( $item[2] ) || empty( $item[0] ) ) {
                    continue;
                }

                $menu_slug = $item[2];
                $menu_title = wp_strip_all_tags( $item[0] );

                if ( strpos( $item[4] ?? '', 'wp-menu-separator' ) !== false ) {
                    continue;
                }

                $sub_items = array();
                if ( ! empty( $submenu[ $menu_slug ] ) && is_array( $submenu[ $menu_slug ] ) ) {
                    foreach ( $submenu[ $menu_slug ] as $sub_item ) {
                        if ( empty( $sub_item[2] ) || empty( $sub_item[0] ) ) {
                            continue;
                        }
                        $sub_items[] = array(
                            'slug'  => $sub_item[2],
                            'title' => wp_strip_all_tags( $sub_item[0] )
                        );
                    }
                }

                $all_menus[] = array(
                    'slug'     => $menu_slug,
                    'title'    => $menu_title,
                    'icon'     => $item[6] ?? 'dashicons-admin-generic',
                    'submenus' => $sub_items
                );
            }
        }

        // Fallback default menu structure for standard WordPress & WooCommerce if menu array is empty
        if ( empty( $all_menus ) ) {
            $all_menus = array(
                array(
                    'slug'     => 'edit.php?post_type=product',
                    'title'    => 'محصولات',
                    'icon'     => 'dashicons-products',
                    'submenus' => array(
                        array( 'slug' => 'edit.php?post_type=product', 'title' => 'همه محصولات' ),
                        array( 'slug' => 'post-new.php?post_type=product', 'title' => 'افزودن جدید' ),
                        array( 'slug' => 'edit-tags.php?taxonomy=product_cat&post_type=product', 'title' => 'دسته‌بندی‌ها' ),
                    )
                ),
                array(
                    'slug'     => 'admin.php?page=wc-orders',
                    'title'    => 'سفارشات',
                    'icon'     => 'dashicons-cart',
                    'submenus' => array(
                        array( 'slug' => 'admin.php?page=wc-orders', 'title' => 'مشاهده سفارشات' ),
                    )
                ),
                array(
                    'slug'     => 'edit.php',
                    'title'    => 'نوشته‌ها',
                    'icon'     => 'dashicons-admin-post',
                    'submenus' => array(
                        array( 'slug' => 'edit.php', 'title' => 'همه نوشته‌ها' ),
                        array( 'slug' => 'post-new.php', 'title' => 'افزودن نوشته' ),
                        array( 'slug' => 'edit-tags.php?taxonomy=category', 'title' => 'دسته‌بندی‌ها' ),
                    )
                ),
                array(
                    'slug'     => 'edit.php?post_type=page',
                    'title'    => 'برگه‌ها',
                    'icon'     => 'dashicons-admin-page',
                    'submenus' => array(
                        array( 'slug' => 'edit.php?post_type=page', 'title' => 'همه برگه‌ها' ),
                        array( 'slug' => 'post-new.php?post_type=page', 'title' => 'افزودن برگه' ),
                    )
                ),
                array(
                    'slug'     => 'upload.php',
                    'title'    => 'رسانه',
                    'icon'     => 'dashicons-admin-media',
                    'submenus' => array(
                        array( 'slug' => 'upload.php', 'title' => 'کتابخانه رسانه' ),
                        array( 'slug' => 'media-new.php', 'title' => 'افزودن فایل' ),
                    )
                )
            );
        }

        return $all_menus;
    }

    /**
     * Get allowed menus for current user
     */
    public static function get_allowed_menus_for_user( $user_id = null ) {
        if ( ! $user_id ) {
            $user_id = get_current_user_id();
        }

        if ( user_can( $user_id, 'administrator' ) ) {
            return 'all';
        }

        $allowed = get_user_meta( $user_id, 'eafd_allowed_menus', true );
        return is_array( $allowed ) ? $allowed : array();
    }

    /**
     * Filter admin menus based on user permission
     */
    public function filter_admin_menus() {
        if ( current_user_can( 'administrator' ) ) {
            return;
        }

        $user_id = get_current_user_id();
        $user = get_userdata( $user_id );
        if ( ! $user || ! in_array( 'eafd_operator', (array) $user->roles, true ) ) {
            return;
        }

        $allowed = self::get_allowed_menus_for_user( $user_id );
        if ( $allowed === 'all' ) {
            return;
        }

        global $menu, $submenu;

        $parents_with_allowed_subs = array();
        if ( is_array( $submenu ) ) {
            foreach ( $submenu as $parent_slug => $sub_list ) {
                foreach ( $sub_list as $sub_item ) {
                    $sub_slug = $sub_item[2] ?? '';
                    $full_sub_key = $parent_slug . '::' . $sub_slug;
                    if ( in_array( $sub_slug, $allowed, true ) || in_array( $full_sub_key, $allowed, true ) ) {
                        $parents_with_allowed_subs[] = $parent_slug;
                        break;
                    }
                }
            }
        }

        if ( is_array( $menu ) ) {
            foreach ( $menu as $key => $item ) {
                $menu_slug = $item[2] ?? '';
                if ( ! in_array( $menu_slug, $allowed, true ) && ! in_array( $menu_slug, $parents_with_allowed_subs, true ) ) {
                    unset( $menu[ $key ] );
                }
            }
        }

        if ( is_array( $submenu ) ) {
            foreach ( $submenu as $parent_slug => $sub_list ) {
                foreach ( $sub_list as $sub_key => $sub_item ) {
                    $sub_slug = $sub_item[2] ?? '';
                    $full_sub_key = $parent_slug . '::' . $sub_slug;
                    if ( ! in_array( $sub_slug, $allowed, true ) && ! in_array( $full_sub_key, $allowed, true ) && ! in_array( $parent_slug, $allowed, true ) ) {
                        unset( $submenu[ $parent_slug ][ $sub_key ] );
                    }
                }
            }
        }
    }
}

new EAFD_Custom_Admin_Access_Control();
