<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$message = '';

if ( isset( $_POST['eafd_save_meta_settings_nonce'] ) && wp_verify_nonce( $_POST['eafd_save_meta_settings_nonce'], 'eafd_save_meta_settings' ) ) {
    $page_elements = isset( $_POST['page_elements'] ) && is_array( $_POST['page_elements'] ) ? $_POST['page_elements'] : array();

    // Sanitize element selections
    $clean_elements = array();
    foreach ( $page_elements as $page_key => $elements ) {
        if ( is_array( $elements ) ) {
            $clean_elements[ sanitize_key( $page_key ) ] = array_map( 'sanitize_key', $elements );
        }
    }

    $custom_selectors = sanitize_textarea_field( $_POST['custom_css_selectors'] ?? '' );

    update_option( 'eafd_page_elements_hidden', $clean_elements );
    update_option( 'eafd_meta_custom_css_selectors', $custom_selectors );

    $message = 'تنظیمات کنترل ریز صفحات با موفقیت ذخیره شد.';
}

$hidden_elements = get_option( 'eafd_page_elements_hidden', array() );
if ( ! is_array( $hidden_elements ) ) {
    $hidden_elements = array();
}

$custom_selectors = get_option( 'eafd_meta_custom_css_selectors', '' );

// Configurable pages and their recognizable sub-items
$pages_config = array(
    'product' => array(
        'title'    => '🛒 افزودن / ویرایش محصول (ووکامرس)',
        'elements' => array(
            'title'         => array( 'label' => 'عنوان محصول', 'selector' => '#titlewrap' ),
            'editor'        => array( 'label' => 'توضیحات محصول (ویرایشگر اصلی)', 'selector' => '#postdivrich' ),
            'excerpt'       => array( 'label' => 'توضیحات کوتاه محصول', 'selector' => '#postexcerpt' ),
            'publish'       => array( 'label' => 'باکس انتشار و بروزرسانی', 'selector' => '#submitdiv' ),
            'price'         => array( 'label' => 'قیمت اصلی و قیمت فروش ویژه', 'selector' => '._regular_price_field, ._sale_price_field' ),
            'product_data'  => array( 'label' => 'اطلاعات محصول (قیمت، انبار، حمل و نقل)', 'selector' => '#woocommerce-product-data' ),
            'image'         => array( 'label' => 'تصویر شاخص محصول', 'selector' => '#postimagediv' ),
            'gallery'       => array( 'label' => 'گالری تصاویر محصول', 'selector' => '#woocommerce-product-images' ),
            'categories'    => array( 'label' => 'دسته‌بندی‌های محصول', 'selector' => '#taxonomy-product_cat' ),
            'tags'          => array( 'label' => 'برچسب‌های محصول', 'selector' => '#tagsdiv-product_tag' ),
            'seo'           => array( 'label' => 'باکس سئو (Yoast / RankMath)', 'selector' => '#wpseo_meta, #rank_math_metabox' ),
            'custom_fields' => array( 'label' => 'زمینه های دلخواه', 'selector' => '#postcustom' ),
            'slug'          => array( 'label' => 'نامک / نام کوتاه (Slug)', 'selector' => '#edit-slug-box' ),
        )
    ),
    'post' => array(
        'title'    => '📝 افزودن / ویرایش نوشته‌ها',
        'elements' => array(
            'title'         => array( 'label' => 'عنوان نوشته', 'selector' => '#titlewrap' ),
            'editor'        => array( 'label' => 'متن نوشته (ویرایشگر)', 'selector' => '#postdivrich' ),
            'publish'       => array( 'label' => 'باکس انتشار', 'selector' => '#submitdiv' ),
            'image'         => array( 'label' => 'تصویر شاخص نوشته', 'selector' => '#postimagediv' ),
            'categories'    => array( 'label' => 'دسته‌بندی‌های نوشته', 'selector' => '#categorydiv' ),
            'tags'          => array( 'label' => 'برچسب‌های نوشته', 'selector' => '#tagsdiv-post_tag' ),
            'excerpt'       => array( 'label' => 'چکیده نوشته', 'selector' => '#postexcerpt' ),
            'author'        => array( 'label' => 'نویسنده نوشته', 'selector' => '#authordiv' ),
            'seo'           => array( 'label' => 'باکس سئو', 'selector' => '#wpseo_meta, #rank_math_metabox' ),
        )
    ),
    'page' => array(
        'title'    => '📄 افزودن / ویرایش برگه‌ها',
        'elements' => array(
            'title'         => array( 'label' => 'عنوان برگه', 'selector' => '#titlewrap' ),
            'editor'        => array( 'label' => 'محتوای برگه', 'selector' => '#postdivrich' ),
            'publish'       => array( 'label' => 'باکس انتشار', 'selector' => '#submitdiv' ),
            'attributes'    => array( 'label' => 'صفات برگه (مادر/قالب)', 'selector' => '#pageparentdiv' ),
            'image'         => array( 'label' => 'تصویر شاخص برگه', 'selector' => '#postimagediv' ),
            'seo'           => array( 'label' => 'باکس سئو', 'selector' => '#wpseo_meta, #rank_math_metabox' ),
        )
    ),
    'shop_order' => array(
        'title'    => '📦 مشاهده و ویرایش سفارشات ووکامرس',
        'elements' => array(
            'order_data'    => array( 'label' => 'جزئیات سفارش و آدرس مشتری', 'selector' => '#woocommerce-order-data' ),
            'order_items'   => array( 'label' => 'آیتم‌ها و اقلام سفارش', 'selector' => '#woocommerce-order-items' ),
            'order_actions' => array( 'label' => 'کارهای سفارش (ارسال مجدد ایمیل و...)', 'selector' => '#woocommerce-order-actions' ),
            'order_notes'   => array( 'label' => 'یادداشت‌های سفارش', 'selector' => '#woocommerce-order-notes' ),
            'order_save'    => array( 'label' => 'دکمه ذخیره / بروزرسانی سفارش', 'selector' => '#submitdiv' ),
        )
    )
);
?>

<div class="wrap eafd-admin-wrap" style="direction: rtl; font-family: Vazirmatn, sans-serif;">
    <h1 style="margin-bottom: 20px; font-weight: 700; color: #1d2327;">کنترل ریزِ صفحات (مخفی‌سازی آیتم‌های مشخص)</h1>

    <?php if ( ! empty( $message ) ) : ?>
        <div class="notice notice-success is-dismissible"><p><?php echo esc_html( $message ); ?></p></div>
    <?php endif; ?>

    <form method="post" action="">
        <?php wp_nonce_field( 'eafd_save_meta_settings', 'eafd_save_meta_settings_nonce' ); ?>

        <p style="font-size: 14px; color: #64748b; margin-bottom: 20px;">
            در این بخش می‌توانید مشخص کنید اپراتور در هر یک از صفحات، به کدام بخش‌ها دسترسی <strong>نداشته باشد</strong> (آیتم‌های تیک خورده برای اپراتور مخفی می‌شوند):
        </p>

        <div style="display: flex; flex-direction: column; gap: 20px; max-width: 900px;">
            <?php foreach ( $pages_config as $page_key => $page_info ) : ?>
                <div style="background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                    <h2 style="font-size: 16px; margin-top: 0; margin-bottom: 15px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px; color: #1e293b;">
                        <?php echo esc_html( $page_info['title'] ); ?>
                    </h2>

                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 12px;">
                        <?php foreach ( $page_info['elements'] as $elem_key => $elem_info ) : ?>
                            <?php
                            $is_hidden = isset( $hidden_elements[ $page_key ] ) && in_array( $elem_key, $hidden_elements[ $page_key ], true );
                            ?>
                            <label style="font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 8px; color: #334155; background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px solid #f1f5f9;">
                                <input type="checkbox" name="page_elements[<?php echo esc_attr( $page_key ); ?>][]" value="<?php echo esc_attr( $elem_key ); ?>" <?php checked( $is_hidden ); ?>>
                                <span>مخفی کردن: <strong><?php echo esc_html( $elem_info['label'] ); ?></strong></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Custom CSS Selectors Card -->
            <div style="background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                <h2 style="font-size: 16px; margin-top: 0; margin-bottom: 10px; color: #1e293b;">
                    🔍 مخفی‌سازی سفارشی با آیدی یا کلاس CSS:
                </h2>
                <p style="color: #64748b; font-size: 13px; margin-bottom: 15px;">
                    کلاس‌ها یا آیدی‌های CSS دلخواه را با ویرگول (,) جدا کنید. مثال: <code>.product-addon-field, #wp-admin-bar-seo</code>
                </p>
                <textarea name="custom_css_selectors" rows="3" style="width: 100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px; font-family: monospace; direction: ltr;"><?php echo esc_textarea( $custom_selectors ); ?></textarea>
            </div>

            <p style="margin-top: 10px;">
                <input type="submit" class="button button-primary button-large" style="border-radius: 20px; padding: 8px 30px; height: auto; font-size: 15px;" value="💾 ذخیره تنظیمات ریز صفحات">
            </p>
        </div>
    </form>
</div>
