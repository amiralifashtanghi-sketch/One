<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class EAFD_Custom_Admin_Meta_Hider {

    public function __construct() {
        add_action( 'admin_head', array( $this, 'inject_iframe_and_meta_styles' ) );
    }

    public function inject_iframe_and_meta_styles() {
        $is_iframe = ( isset( $_REQUEST['eafd_iframe'] ) && $_REQUEST['eafd_iframe'] == 1 )
            || ( ! empty( $_SERVER['HTTP_REFERER'] ) && strpos( $_SERVER['HTTP_REFERER'], 'eafd_iframe=1' ) !== false );

        $hide_seo = get_option( 'eafd_meta_hide_seo', 0 );
        $hide_custom_fields = get_option( 'eafd_meta_hide_custom_fields', 0 );
        $hide_slug = get_option( 'eafd_meta_hide_slug', 0 );
        $hide_author = get_option( 'eafd_meta_hide_author', 0 );
        $custom_selectors = get_option( 'eafd_meta_custom_css_selectors', '' );

        $css = '';

        if ( $is_iframe ) {
            $css .= '
                #adminmenumain, #wpadminbar, #wpfooter, .notice-dismiss { display: none !important; }
                html.wp-toolbar { padding-top: 0 !important; }
                #wpcontent, #wpbody-content { margin-right: 0 !important; margin-left: 0 !important; padding: 10px !important; }
                body { background: #ffffff !important; overflow-x: hidden !important; }
            ';
        }

        // Apply meta hiding rules if user is operator
        $user_id = get_current_user_id();
        $user = get_userdata( $user_id );
        $is_operator = $user && in_array( 'eafd_operator', (array) $user->roles, true );

        if ( $is_operator ) {
            if ( $hide_seo ) {
                $css .= '#wpseo_meta, #rank_math_metabox, .yoast-seo-issue-counter { display: none !important; } ';
            }
            if ( $hide_custom_fields ) {
                $css .= '#postcustom, #postcustomstuff { display: none !important; } ';
            }
            if ( $hide_slug ) {
                $css .= '#slugdiv, #edit-slug-box { display: none !important; } ';
            }
            if ( $hide_author ) {
                $css .= '#authordiv { display: none !important; } ';
            }
            if ( ! empty( trim( $custom_selectors ) ) ) {
                $sanitized_selectors = wp_strip_all_tags( $custom_selectors );
                $css .= $sanitized_selectors . ' { display: none !important; } ';
            }
        }

        if ( ! empty( $css ) ) {
            echo '<style id="eafd-custom-admin-styles">' . $css . '</style>';
        }
    }
}

new EAFD_Custom_Admin_Meta_Hider();
