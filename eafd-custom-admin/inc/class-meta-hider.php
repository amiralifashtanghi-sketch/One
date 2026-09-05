<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class EAFD_Custom_Admin_Meta_Hider {

    public function __construct() {
        add_action( 'admin_head', array( $this, 'inject_iframe_and_meta_styles' ) );
        add_action( 'admin_footer', array( $this, 'inject_iframe_js_persistence' ) );
        add_filter( 'woocommerce_admin_features', array( $this, 'disable_woocommerce_admin_header' ) );
    }

    public function disable_woocommerce_admin_header( $features ) {
        $user_id = get_current_user_id();
        $user = get_userdata( $user_id );
        $is_operator = $user && in_array( 'eafd_operator', (array) $user->roles, true );
        $is_iframe = $is_operator || ( isset( $_REQUEST['eafd_iframe'] ) && $_REQUEST['eafd_iframe'] == 1 );

        if ( $is_iframe ) {
            return array();
        }
        return $features;
    }

    public function inject_iframe_and_meta_styles() {
        $user_id = get_current_user_id();
        $user = get_userdata( $user_id );
        $is_operator = $user && in_array( 'eafd_operator', (array) $user->roles, true );

        $is_iframe = $is_operator || ( isset( $_REQUEST['eafd_iframe'] ) && $_REQUEST['eafd_iframe'] == 1 )
            || ( ! empty( $_SERVER['HTTP_REFERER'] ) && strpos( $_SERVER['HTTP_REFERER'], 'eafd_iframe=1' ) !== false );

        $hide_seo = get_option( 'eafd_meta_hide_seo', 0 );
        $hide_custom_fields = get_option( 'eafd_meta_hide_custom_fields', 0 );
        $hide_slug = get_option( 'eafd_meta_hide_slug', 0 );
        $hide_author = get_option( 'eafd_meta_hide_author', 0 );
        $custom_selectors = get_option( 'eafd_meta_custom_css_selectors', '' );

        $css = '';

        if ( $is_iframe ) {
            $css .= '
                #adminmenumain, #wpadminbar, #wpfooter, .notice-dismiss, #screen-meta, #screen-meta-links,
                #woocommerce-embedded-root, .woocommerce-layout__header, .woocommerce-layout__header-wrapper,
                .woocommerce-embed-page #wpbody-content .woocommerce-layout,
                .woocommerce-layout__primary-header { display: none !important; }
                html.wp-toolbar { padding-top: 0 !important; }
                #wpcontent, #wpbody-content { margin-right: 0 !important; margin-left: 0 !important; padding: 10px !important; }
                .woocommerce-embed-page #wpbody-content { padding-top: 0 !important; }
                body { background: #ffffff !important; overflow-x: hidden !important; }
            ';
        }

        // Apply meta hiding rules if user is operator

        if ( $is_operator ) {
            // Legacy / simple checkboxes
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

            // Granular page elements mapping
            $pages_config = array(
                'product' => array(
                    'title'         => '#titlewrap',
                    'editor'        => '#postdivrich',
                    'excerpt'       => '#postexcerpt',
                    'publish'       => '#submitdiv',
                    'price'         => '._regular_price_field, ._sale_price_field',
                    'product_data'  => '#woocommerce-product-data',
                    'image'         => '#postimagediv',
                    'gallery'       => '#woocommerce-product-images',
                    'categories'    => '#taxonomy-product_cat',
                    'tags'          => '#tagsdiv-product_tag',
                    'seo'           => '#wpseo_meta, #rank_math_metabox',
                    'custom_fields' => '#postcustom',
                    'slug'          => '#edit-slug-box',
                ),
                'post' => array(
                    'title'         => '#titlewrap',
                    'editor'        => '#postdivrich',
                    'publish'       => '#submitdiv',
                    'image'         => '#postimagediv',
                    'categories'    => '#categorydiv',
                    'tags'          => '#tagsdiv-post_tag',
                    'excerpt'       => '#postexcerpt',
                    'author'        => '#authordiv',
                    'seo'           => '#wpseo_meta, #rank_math_metabox',
                ),
                'page' => array(
                    'title'         => '#titlewrap',
                    'editor'        => '#postdivrich',
                    'publish'       => '#submitdiv',
                    'attributes'    => '#pageparentdiv',
                    'image'         => '#postimagediv',
                    'seo'           => '#wpseo_meta, #rank_math_metabox',
                ),
                'shop_order' => array(
                    'order_data'    => '#woocommerce-order-data',
                    'order_items'   => '#woocommerce-order-items',
                    'order_actions' => '#woocommerce-order-actions',
                    'order_notes'   => '#woocommerce-order-notes',
                    'order_save'    => '#submitdiv',
                )
            );

            $hidden_elements = get_option( 'eafd_page_elements_hidden', array() );
            if ( is_array( $hidden_elements ) ) {
                foreach ( $hidden_elements as $p_key => $elem_keys ) {
                    if ( isset( $pages_config[ $p_key ] ) && is_array( $elem_keys ) ) {
                        foreach ( $elem_keys as $e_key ) {
                            if ( isset( $pages_config[ $p_key ][ $e_key ] ) ) {
                                $css .= $pages_config[ $p_key ][ $e_key ] . ' { display: none !important; } ';
                            }
                        }
                    }
                }
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

    public function inject_iframe_js_persistence() {
        $user_id = get_current_user_id();
        $user = get_userdata( $user_id );
        $is_operator = $user && in_array( 'eafd_operator', (array) $user->roles, true );

        if ( ! $is_operator && ! ( isset( $_REQUEST['eafd_iframe'] ) && $_REQUEST['eafd_iframe'] == 1 ) ) {
            return;
        }

        ?>
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Append eafd_iframe=1 to internal links and forms
            function preserveIframeParam() {
                document.querySelectorAll("a[href]").forEach(function(link) {
                    var href = link.getAttribute("href");
                    if (href && !href.startsWith("#") && !href.startsWith("javascript:") && href.indexOf("eafd_iframe=1") === -1) {
                        link.setAttribute("href", href + (href.indexOf("?") !== -1 ? "&" : "?") + "eafd_iframe=1");
                    }
                });
                document.querySelectorAll("form").forEach(function(form) {
                    if (!form.querySelector("input[name='eafd_iframe']")) {
                        var hiddenInput = document.createElement("input");
                        hiddenInput.type = "hidden";
                        hiddenInput.name = "eafd_iframe";
                        hiddenInput.value = "1";
                        form.appendChild(hiddenInput);
                    }
                });
            }
            preserveIframeParam();
            setInterval(preserveIframeParam, 1500);
        });
        </script>
        <?php
    }
}

new EAFD_Custom_Admin_Meta_Hider();
