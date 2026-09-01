<?php
/**
 * Custom WooCommerce Cart Page Template - Modern Three-Style Design
 */
if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_before_cart');
?>

<div class="eafd-wc-container cart-container-wrapper" style="max-width: 1100px; margin: 30px auto; padding: 0 15px; direction: rtl; font-family: var(--font, 'Vazirmatn', sans-serif);">
    <h2 class="cart-header-title" style="font-size: 24px; font-weight: 800; color: var(--blue-primary, #0d1b2a); margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-shopping-cart" style="color: var(--turquoise, #1abc9c);"></i> سبد خرید ووکامرس | ترکیب سه سبک
    </h2>

    <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
        <?php do_action('woocommerce_before_cart_table'); ?>

        <div class="cart-grid-layout" style="display: grid; grid-template-columns: 1fr 340px; gap: 24px;">
            <!-- Cart Items List -->
            <div class="cart-items-column" style="display: flex; flex-direction: column; gap: 20px;">
                <?php do_action('woocommerce_before_cart_contents'); ?>

                <?php
                foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                    $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                    if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                        $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                        $sku = $_product->get_sku();
                        $rating = $_product->get_average_rating();
                        ?>
                        <div class="car-card eafd-neo-card" style="background: var(--neo-bg, #f0f4f8); border-radius: 20px; padding: 18px 22px; box-shadow: 6px 6px 16px rgba(0,0,0,0.06), -6px -6px 16px rgba(255,255,255,0.8); display: flex; align-items: center; justify-content: space-between; gap: 20px; position: relative;">

                            <!-- Right / Start: Image & Product Details -->
                            <div style="display: flex; align-items: center; gap: 16px; flex: 1; min-width: 0;">
                                <div class="car-img" style="width: 85px; height: 85px; min-width: 85px; border-radius: 18px; overflow: hidden; background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.08); display: flex; align-items: center; justify-content: center;">
                                    <?php
                                    $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('thumbnail', array('style' => 'width:100%; height:100%; object-fit:cover;')), $cart_item, $cart_item_key);
                                    if (!$product_permalink) {
                                        echo $thumbnail;
                                    } else {
                                        printf('<a href="%s" style="display:block; width:100%%; height:100%%;">%s</a>', esc_url($product_permalink), $thumbnail);
                                    }
                                    ?>
                                </div>

                                <div class="car-info" style="display: flex; flex-direction: column; gap: 6px; min-width: 0;">
                                    <h3 style="font-size: 16px; font-weight: 800; color: var(--blue-primary, #0d1b2a); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <?php
                                        if (!$product_permalink) {
                                            echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key));
                                        } else {
                                            echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s" style="color: var(--blue-primary, #0d1b2a); text-decoration: none;">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                        }
                                        ?>
                                    </h3>

                                    <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--text-muted, #718096); flex-wrap: wrap;">
                                        <?php if ($sku) : ?>
                                            <span style="background: rgba(0,0,0,0.04); padding: 2px 8px; border-radius: 6px;"><i class="fas fa-barcode"></i> کد: <?php echo esc_html($sku); ?></span>
                                        <?php endif; ?>
                                        <?php if ($rating > 0) : ?>
                                            <span style="color: #f39c12; font-weight: bold;"><i class="fas fa-star"></i> <?php echo number_format($rating, 1); ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <?php
                                    // Meta data
                                    echo wc_get_formatted_cart_item_data($cart_item);
                                    ?>
                                </div>
                            </div>

                            <!-- Left / End: Price Pill & Quantity Controller -->
                            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 12px;">
                                <div class="car-price" style="background: rgba(44, 123, 229, 0.12); color: #1a5276; font-weight: 800; font-size: 15px; padding: 6px 14px; border-radius: 30px; white-space: nowrap;">
                                    <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
                                </div>

                                <div class="quantity-wrapper" style="display: flex; align-items: center; gap: 10px; background: rgba(255,255,255,0.7); padding: 4px 10px; border-radius: 25px; box-shadow: inset 2px 2px 5px rgba(0,0,0,0.05), inset -2px -2px 5px rgba(255,255,255,0.8);">
                                    <?php
                                    echo apply_filters(
                                        'woocommerce_cart_item_remove_link',
                                        sprintf(
                                            '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s" style="color: #e74c3c; font-size: 14px; padding: 4px; border-radius: 50%%; display: flex; align-items: center; justify-content: center; width: 26px; height: 26px; background: rgba(231,76,60,0.1);"><i class="fas fa-trash-alt"></i></a>',
                                            esc_url(wc_get_cart_remove_url($cart_item_key)),
                                            esc_attr(sprintf(__('حذف %s از سبد خرید', 'woocommerce'), $_product->get_name())),
                                            esc_attr($product_id),
                                            esc_attr($_product->get_sku())
                                        ),
                                        $cart_item_key
                                    );
                                    ?>

                                    <div class="cart-quantity-input-box">
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
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>

                <?php do_action('woocommerce_cart_contents'); ?>

                <!-- Coupon & Actions -->
                <div class="cart-actions-row" style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px; flex-wrap: wrap; gap: 15px;">
                    <?php if (wc_coupons_enabled()) { ?>
                        <div class="actions coupon-form-group" style="display: flex; gap: 10px;">
                            <input type="text" name="coupon_code" class="input-text neumor-input" id="coupon_code" value="" placeholder="<?php esc_attr_e('کد تخفیف', 'woocommerce'); ?>" style="padding: 10px 18px; border-radius: 30px; border: none; background: #e0e5ec; box-shadow: inset 3px 3px 6px #a3b1c6, inset -3px -3px 6px #ffffff; outline: none; font-size: 14px;" />
                            <button type="submit" class="button btn-neo eafd-btn-skeuo" name="apply_coupon" value="<?php esc_attr_e('اعمال کوپن', 'woocommerce'); ?>">
                                <?php esc_html_e('اعمال کوپن', 'woocommerce'); ?>
                            </button>
                            <?php do_action('woocommerce_cart_coupon'); ?>
                        </div>
                    <?php } ?>

                    <button type="submit" class="button btn-neo eafd-btn-skeuo" name="update_cart" value="<?php esc_attr_e('به‌روزرسانی سبد خرید', 'woocommerce'); ?>">
                        <?php esc_html_e('به‌روزرسانی سبد خرید', 'woocommerce'); ?>
                    </button>

                    <?php do_action('woocommerce_cart_actions'); ?>
                    <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                </div>

                <?php do_action('woocommerce_after_cart_contents'); ?>
            </div>

            <!-- Cart Summary Sidebar -->
            <div class="cart-sidebar-column">
                <div class="cart-summary eafd-neo-card" style="background: var(--neo-bg, #f0f4f8); border-radius: 24px; padding: 24px; box-shadow: 8px 8px 20px rgba(0,0,0,0.07), -8px -8px 20px rgba(255,255,255,0.9); position: sticky; top: 20px;">
                    <h3 style="font-size: 20px; font-weight: 800; color: var(--blue-primary, #0d1b2a); margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
                        <span>جمع کل سبد 🧾</span>
                    </h3>

                    <div class="cart-totals-details">
                        <?php woocommerce_cart_totals(); ?>
                    </div>

                    <div style="margin-top: 20px;">
                        <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="checkout-button button alt wc-forward btn-neo neumor-btn eafd-btn-skeuo" style="width: 100%; padding: 14px 20px; font-size: 16px; border-radius: 30px; text-align: center; justify-content: center; display: flex; align-items: center; gap: 8px; text-decoration: none;">
                            <span>ادامه جهت تسویه‌حساب</span>
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php do_action('woocommerce_after_cart_table'); ?>
    </form>
</div>

<?php do_action('woocommerce_after_cart'); ?>
