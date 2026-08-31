<?php
/**
 * Custom WooCommerce Cart Page Template
 */
if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_before_cart');
?>

<div class="eafd-wc-container" style="max-width: 1200px; margin: 30px auto;">
    <h2 style="font-size: 24px; font-weight: 800; color: var(--blue-primary); margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-shopping-cart" style="color: var(--turquoise);"></i> سبد خرید شما
    </h2>

    <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
        <?php do_action('woocommerce_before_cart_table'); ?>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
            <!-- Cart Items List -->
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <?php do_action('woocommerce_before_cart_contents'); ?>

                <?php
                foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                    $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                    if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                        $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                        ?>
                        <div class="eafd-neo-card" style="display: flex; align-items: center; justify-content: space-between; gap: 15px; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <div style="width: 70px; height: 70px; border-radius: 12px; overflow: hidden; background: #fff; border: 1px solid rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: center;">
                                    <?php
                                    $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                                    if (!$product_permalink) {
                                        echo $thumbnail;
                                    } else {
                                        printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail);
                                    }
                                    ?>
                                </div>
                                <div>
                                    <h4 style="font-size: 16px; font-weight: 700; color: var(--blue-primary); margin-bottom: 5px;">
                                        <?php
                                        if (!$product_permalink) {
                                            echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
                                        } else {
                                            echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s" style="color: var(--blue-primary); text-decoration: none;">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                        }
                                        ?>
                                    </h4>
                                    <div style="font-size: 14px; font-weight: 600; color: var(--turquoise);">
                                        <?php echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); ?>
                                    </div>
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 15px;">
                                <div style="background: rgba(255,255,255,0.8); border: 1px solid rgba(0,0,0,0.08); border-radius: 10px; padding: 4px 8px;">
                                    <?php
                                    if ($_product->is_sold_individually()) {
                                        $min_quantity = 1;
                                        $max_quantity = 1;
                                    } else {
                                        $min_quantity = 0;
                                        $max_quantity = $_product->get_max_purchase_quantity();
                                    }

                                    $product_quantity = woocommerce_quantity_input(
                                        array(
                                            'input_name'   => "cart[{$cart_item_key}][qty]",
                                            'input_value'  => $cart_item['quantity'],
                                            'max_value'    => $max_quantity,
                                            'min_value'    => $min_quantity,
                                            'product_name' => $_product->get_name(),
                                        ),
                                        $_product,
                                        false
                                    );

                                    echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item);
                                    ?>
                                </div>

                                <div style="font-weight: 800; font-size: 16px; color: var(--blue-primary);">
                                    <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
                                </div>

                                <?php
                                echo apply_filters(
                                    'woocommerce_cart_item_remove_link',
                                    sprintf(
                                        '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s" style="color: #e74c3c; font-size: 18px; padding: 8px;"><i class="fas fa-trash-alt"></i></a>',
                                        esc_url(wc_get_cart_remove_url($cart_item_key)),
                                        esc_attr(sprintf(__('حذف %s از سبد خرید', 'woocommerce'), $_product->get_name())),
                                        esc_attr($product_id),
                                        esc_attr($_product->get_sku())
                                    ),
                                    $cart_item_key
                                );
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>

                <?php do_action('woocommerce_cart_contents'); ?>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px; flex-wrap: wrap; gap: 15px;">
                    <?php if (wc_coupons_enabled()) { ?>
                        <div class="actions" style="display: flex; gap: 10px;">
                            <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e('کد تخفیف', 'woocommerce'); ?>" style="padding: 10px 15px; border-radius: 10px; border: 1px solid #cbd5e1;" />
                            <button type="submit" class="button eafd-btn-skeuo" name="apply_coupon" value="<?php esc_attr_e('اعمال کوپن', 'woocommerce'); ?>">
                                <?php esc_html_e('اعمال کوپن', 'woocommerce'); ?>
                            </button>
                            <?php do_action('woocommerce_cart_coupon'); ?>
                        </div>
                    <?php } ?>

                    <button type="submit" class="button eafd-btn-skeuo" name="update_cart" value="<?php esc_attr_e('به‌روزرسانی سبد خرید', 'woocommerce'); ?>">
                        <?php esc_html_e('به‌روزرسانی سبد خرید', 'woocommerce'); ?>
                    </button>

                    <?php do_action('woocommerce_cart_actions'); ?>
                    <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                </div>

                <?php do_action('woocommerce_after_cart_contents'); ?>
            </div>

            <!-- Cart Totals Sidebar -->
            <div>
                <div class="eafd-neo-card" style="position: sticky; top: 20px;">
                    <h3 style="font-size: 18px; font-weight: 800; color: var(--blue-primary); border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 12px; margin-bottom: 15px;">
                        خلاصه صورت‌حساب
                    </h3>
                    <?php woocommerce_cart_totals(); ?>
                </div>
            </div>
        </div>

        <?php do_action('woocommerce_after_cart_table'); ?>
    </form>
</div>

<?php do_action('woocommerce_after_cart'); ?>
