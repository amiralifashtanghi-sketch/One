<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class EAFD_Custom_Admin_Access_Control {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'filter_admin_menus' ), 99999 );
        add_action( 'admin_init', array( $this, 'redirect_and_enforce_page_access' ) );
        add_filter( 'user_has_cap', array( $this, 'grant_operator_allowed_capabilities' ), 10, 4 );
    }

    /**
     * Dynamically grant essential capabilities for allowed operator menus so WP core/plugin page checks pass
     */
    public function grant_operator_allowed_capabilities( $allcaps, $caps, $args, $user ) {
        static $in_grant = false;
        if ( $in_grant ) {
            return $allcaps;
        }

        if ( empty( $user->ID ) ) {
            return $allcaps;
        }

        if ( in_array( 'administrator', (array) $user->roles, true ) ) {
            return $allcaps;
        }

        if ( ! in_array( 'eafd_operator', (array) $user->roles, true ) ) {
            return $allcaps;
        }

        $in_grant = true;
        $allowed = get_user_meta( $user->ID, 'eafd_allowed_menus', true );
        $in_grant = false;

        if ( empty( $allowed ) || ! is_array( $allowed ) ) {
            return $allcaps;
        }

        // Grant base capabilities for content editing & Rank Math SEO metabox
        $allcaps['read'] = true;
        $allcaps['upload_files'] = true;
        $allcaps['rank_math_onpage_analysis'] = true;
        $allcaps['rank_math_onpage_general'] = true;
        $allcaps['rank_math_onpage_snippet'] = true;
        $allcaps['rank_math_onpage_social'] = true;
        $allcaps['rank_math_onpage_advanced'] = true;
        $allcaps['rank_math_site_analysis'] = true;
        $allcaps['wpseo_bulk_editing'] = true;

        // Dynamically map allowed menu slugs to specific required capabilities
        foreach ( $allowed as $menu_item ) {
            $normalized = self::normalize_slug( $menu_item );

            // Handle submenus formatted as Parent::Child
            if ( strpos( $normalized, '::' ) !== false ) {
                list( $parent_part, $child_part ) = explode( '::', $normalized, 2 );
                $normalized = $child_part;
            }

            // Product & WooCommerce
            if ( strpos( $normalized, 'product' ) !== false || strpos( $normalized, 'wc-orders' ) !== false || strpos( $normalized, 'woocommerce' ) !== false ) {
                $allcaps['manage_woocommerce'] = true;
                $allcaps['edit_products'] = true;
                $allcaps['publish_products'] = true;
                $allcaps['edit_others_products'] = true;
                $allcaps['edit_published_products'] = true;
                $allcaps['read_private_products'] = true;
                $allcaps['manage_product_terms'] = true;
                $allcaps['edit_product_terms'] = true;
                $allcaps['delete_product_terms'] = true;
                $allcaps['assign_product_terms'] = true;
                $allcaps['edit_shop_orders'] = true;
                $allcaps['edit_others_shop_orders'] = true;
                $allcaps['read_private_shop_orders'] = true;
            }

            // Pages
            if ( strpos( $normalized, 'post_type=page' ) !== false ) {
                $allcaps['edit_pages'] = true;
                $allcaps['publish_pages'] = true;
                $allcaps['edit_others_pages'] = true;
                $allcaps['edit_published_pages'] = true;
            }

            // Posts & Categories
            if ( strpos( $normalized, 'edit.php' ) !== false && strpos( $normalized, 'post_type=' ) === false ) {
                $allcaps['edit_posts'] = true;
                $allcaps['publish_posts'] = true;
                $allcaps['edit_others_posts'] = true;
                $allcaps['edit_published_posts'] = true;
                $allcaps['manage_categories'] = true;
            }

            // SEO Plugins
            if ( strpos( $normalized, 'wpseo' ) !== false ) {
                $allcaps['wpseo_bulk_editing'] = true;
                $allcaps['wpseo_manage_options'] = true;
            }
            if ( strpos( $normalized, 'rank_math' ) !== false ) {
                $allcaps['rank_math_general'] = true;
                $allcaps['rank_math_site_analysis'] = true;
            }
        }

        return $allcaps;
    }

    private static function normalize_slug( $slug ) {
        $slug = trim( (string) $slug );
        if ( strpos( $slug, 'admin.php?page=' ) !== false ) {
            $slug = str_replace( 'admin.php?page=', '', $slug );
        }
        return $slug;
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
            if ( isset( $_GET['page'] ) && $_GET['page'] === 'index.php' ) {
                wp_redirect( home_url( '/admin' ) );
            } else {
                wp_redirect( home_url( '/admin' ) );
            }
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

        // Allow profile, logout, user editing own profile, media upload processing, and dashboard home
        if ( in_array( $current_page, array( 'profile.php', 'user-edit.php', 'index.php', 'async-upload.php', 'admin-ajax.php', 'admin-post.php' ), true ) && empty( $page_arg ) ) {
            return;
        }

        // Handle editing existing items on post.php and term.php
        $post_id = isset( $_GET['post'] ) ? intval( $_GET['post'] ) : 0;
        $req_post_type = isset( $_GET['post_type'] ) ? sanitize_text_field( $_GET['post_type'] ) : '';
        if ( empty( $req_post_type ) && $post_id > 0 ) {
            $req_post_type = get_post_type( $post_id );
        }

        // Determine current URI target
        $request_uri = $_SERVER['REQUEST_URI'] ?? '';

        $is_permitted = false;
        foreach ( $allowed as $allowed_item ) {
            $normalized_item = self::normalize_slug( $allowed_item );

            if ( strpos( $normalized_item, '::' ) !== false ) {
                list( $parent, $child ) = explode( '::', $normalized_item, 2 );
                $parent = self::normalize_slug( $parent );
                $child = self::normalize_slug( $child );

                if ( $page_arg && ( $page_arg === $child || $page_arg === $parent ) ) {
                    $is_permitted = true;
                    break;
                }
                if ( strpos( $request_uri, $child ) !== false || strpos( $request_uri, $parent ) !== false ) {
                    $is_permitted = true;
                    break;
                }
            } else {
                if ( $page_arg && $page_arg === $normalized_item ) {
                    $is_permitted = true;
                    break;
                }
                if ( strpos( $request_uri, $normalized_item ) !== false || $current_page === $normalized_item ) {
                    $is_permitted = true;
                    break;
                }
                if ( in_array( $current_page, array( 'post.php', 'post-new.php', 'term.php', 'edit-tags.php' ), true ) ) {
                    if ( ! empty( $req_post_type ) && strpos( $normalized_item, 'post_type=' . $req_post_type ) !== false ) {
                        $is_permitted = true;
                        break;
                    }
                    if ( ( empty( $req_post_type ) || $req_post_type === 'post' ) && $normalized_item === 'edit.php' ) {
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

        // Try reading cached registered menus if empty
        if ( empty( $all_menus ) ) {
            $cached = get_option( 'eafd_registered_admin_menus', array() );
            if ( is_array( $cached ) && ! empty( $cached ) ) {
                $all_menus = $cached;
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
        global $menu, $submenu;

        // Cache full menu structure when admin is logged in (limited to page loads without doing AJAX)
        if ( is_admin() && ! wp_doing_ajax() && current_user_can( 'manage_options' ) && ! empty( $menu ) && is_array( $menu ) ) {
            $cache_menus = array();
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

                $cache_menus[] = array(
                    'slug'     => $menu_slug,
                    'title'    => $menu_title,
                    'icon'     => $item[6] ?? 'dashicons-admin-generic',
                    'submenus' => $sub_items
                );
            }
            if ( ! empty( $cache_menus ) ) {
                update_option( 'eafd_registered_admin_menus', $cache_menus, false );
            }
        }

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
