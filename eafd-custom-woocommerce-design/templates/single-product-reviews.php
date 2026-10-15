<?php
/**
 * Custom Single Product Reviews Template
 */
if (!defined('ABSPATH')) {
    exit;
}

global $product;

if (!comments_open()) {
    return;
}
?>

<div id="reviews" class="eafd-wc-container woocommerce-Reviews" style="margin-top: 30px;">
    <div class="eafd-neo-card" style="margin-bottom: 24px;">
        <h3 style="font-size: 20px; font-weight: 800; color: var(--blue-primary); margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-star" style="color: var(--orange);"></i> نقد و بررسی‌ها و دیدگاه‌های خریداران
        </h3>

        <?php if (have_comments()) : ?>
            <ol class="commentlist" style="list-style: none; padding: 0; margin: 0 0 20px 0;">
                <?php wp_list_comments(apply_filters('woocommerce_product_review_list_args', array('callback' => 'woocommerce_comments'))); ?>
            </ol>

            <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
                <nav class="woocommerce-pagination">
                    <?php
                    paginate_comments_links(
                        apply_filters(
                            'woocommerce_comment_pagination_args',
                            array(
                                'prev_text' => '&rarr;',
                                'next_text' => '&larr;',
                                'type'      => 'list',
                            )
                        )
                    );
                    ?>
                </nav>
            <?php endif; ?>

        <?php else : ?>
            <p style="color: #7f8c8d; font-size: 14px;"><?php esc_html_e('هنوز هیچ دیدگاهی برای این محصول ثبت نشده است.', 'woocommerce'); ?></p>
        <?php endif; ?>
    </div>

    <!-- Review Form -->
    <div class="eafd-neo-card" style="background: var(--glass-bg); backdrop-filter: blur(var(--glass-blur)); border: 1px solid rgba(255, 255, 255, 0.8);">
        <?php if (get_option('woocommerce_review_rating_verification_required') === 'no' || wc_customer_bought_product('', get_current_user_id(), $product->get_id())) : ?>
            <div id="review_form_wrapper">
                <div id="review_form">
                    <?php
                    $commenter    = wp_get_current_commenter();
                    $comment_form = array(
                        'title_reply'         => have_comments() ? esc_html__('افزودن دیدگاه یا نقد جدید', 'woocommerce') : sprintf(esc_html__('اولین نفری باشید که برای "%s" دیدگاه می‌گذارید', 'woocommerce'), get_the_title()),
                        'title_reply_to'      => esc_html__('پاسخ به %s', 'woocommerce'),
                        'title_reply_before'  => '<h4 id="reply-title" class="comment-reply-title" style="font-size: 18px; font-weight: 800; color: var(--blue-primary); margin-bottom: 15px;">',
                        'title_reply_after'   => '</h4>',
                        'comment_notes_after' => '',
                        'label_submit'        => esc_html__('ثبت دیدگاه', 'woocommerce'),
                        'class_submit'        => 'submit eafd-btn-skeuo',
                        'logged_in_as'        => '',
                        'comment_field'       => '',
                    );

                    $name_email_required = (bool) get_option('require_name_email');
                    $fields              = array(
                        'author' => '<p class="comment-form-author" style="margin-bottom: 15px;"><label for="author" style="font-weight: 600; color: var(--blue-primary);">' . esc_html__('نام', 'woocommerce') . ($name_email_required ? ' <span class="required">*</span>' : '') . '</label> ' .
                                    '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" ' . ($name_email_required ? 'required' : '') . ' style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #cbd5e1; background: #fff;" /></p>',
                        'email'  => '<p class="comment-form-email" style="margin-bottom: 15px;"><label for="email" style="font-weight: 600; color: var(--blue-primary);">' . esc_html__('ایمیل', 'woocommerce') . ($name_email_required ? ' <span class="required">*</span>' : '') . '</label> ' .
                                    '<input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" ' . ($name_email_required ? 'required' : '') . ' style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #cbd5e1; background: #fff;" /></p>',
                    );

                    $comment_form['fields'] = $fields;

                    if (wc_review_ratings_enabled()) {
                        $comment_form['comment_field'] = '<div class="comment-form-rating" style="margin-bottom: 15px;"><label for="rating" style="font-weight: 600; color: var(--blue-primary);">' . esc_html__('امتیاز شما', 'woocommerce') . '</label><select name="rating" id="rating" required style="width: 100%; padding: 10px; border-radius: 10px; border: 1px solid #cbd5e1; background: #fff;">
                            <option value="">' . esc_html__('امتیاز دهید&hellip;', 'woocommerce') . '</option>
                            <option value="5">' . esc_html__('عالی', 'woocommerce') . '</option>
                            <option value="4">' . esc_html__('خوب', 'woocommerce') . '</option>
                            <option value="3">' . esc_html__('متوسط', 'woocommerce') . '</option>
                            <option value="2">' . esc_html__('ضعیف', 'woocommerce') . '</option>
                            <option value="1">' . esc_html__('خیلی ضعیف', 'woocommerce') . '</option>
                        </select></div>';
                    }

                    $comment_form['comment_field'] .= '<p class="comment-form-comment" style="margin-bottom: 15px;"><label for="comment" style="font-weight: 600; color: var(--blue-primary);">' . esc_html__('دیدگاه شما', 'woocommerce') . ' <span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="5" required style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #cbd5e1; background: #fff;"></textarea></p>';

                    comment_form(apply_filters('woocommerce_product_review_comment_form_args', $comment_form));
                    ?>
                </div>
            </div>
        <?php else : ?>
            <p class="woocommerce-verification-required"><?php esc_html_e('تنها خریدارانی که این محصول را خریداری کرده‌اند می‌توانند دیدگاه ثبت کنند.', 'woocommerce'); ?></p>
        <?php endif; ?>
    </div>
</div>
