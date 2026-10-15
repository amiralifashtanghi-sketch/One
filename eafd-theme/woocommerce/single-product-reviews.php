<?php
/**
 * Display single product reviews (comments)
 *
 * @package EAFD_Theme
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! comments_open() ) {
	return;
}

?>
<div id="reviews" class="woocommerce-Reviews eafd-reviews-wrapper">
	<div class="eafd-reviews-card">
		<div id="comments" class="eafd-reviews-header">
			<h2 class="woocommerce-Reviews-title eafd-reviews-title">
				نظرات خریداران (<?php echo esc_html( eafd_convert_to_persian_digits( $product->get_review_count() ) ); ?>)
			</h2>

			<?php if ( $product->get_review_count() > 0 ) : ?>
				<div class="eafd-rating-summary-box">
					<span class="eafd-rating-score"><?php echo esc_html( eafd_convert_to_persian_digits( number_format( $product->get_average_rating(), 1 ) ) ); ?></span>
					<div class="eafd-stars-wrapper">
						<?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
					</div>
					<span class="eafd-rating-count">از ۵ ستاره</span>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( have_comments() ) : ?>
			<ol class="commentlist eafd-reviews-list">
				<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
			</ol>

			<?php
			if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
				echo '<nav class="woocommerce-pagination eafd-pagination-wrapper">';
				paginate_comments_links(
					apply_filters(
						'woocommerce_comment_pagination_args',
						array(
							'prev_text' => '&larr;',
							'next_text' => '&rarr;',
							'type'      => 'list',
						)
					)
				);
				echo '</nav>';
			endif;
			?>
		<?php else : ?>
			<div class="eafd-no-reviews-state">
				<p>هنوز هیچ نظری برای این محصول ثبت نشده است. اولین نفری باشید که نظر می‌دهید!</p>
			</div>
		<?php endif; ?>

		<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>
			<div id="review_form_wrapper" class="eafd-review-form-wrapper">
				<div id="review_form">
					<?php
					$commenter    = wp_get_current_commenter();
					$comment_form = array(
						'title_reply'         => have_comments() ? 'ثبت نظر جدید' : 'اولین نظریه خود را بنویسید',
						'title_reply_to'      => 'پاسخ به %s',
						'title_reply_before'  => '<h3 id="reply-title" class="comment-reply-title eafd-form-title">',
						'title_reply_after'   => '</h3>',
						'comment_notes_after' => '',
						'label_submit'        => 'ثبت نظر',
						'class_submit'        => 'submit eafd-btn eafd-btn-primary',
						'submit_button'       => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>',
						'logged_in_as'        => '',
						'comment_field'       => '',
					);

					$account_page_url = wc_get_page_permalink( 'myaccount' );
					if ( $account_page_url ) {
						/* translators: %s opening and closing link tags respectively */
						$comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( esc_html__( 'You must be %1$slogged in%2$s to post a review.', 'woocommerce' ), '<a href="' . esc_url( $account_page_url ) . '">', '</a>' ) . '</p>';
					}

					if ( wc_review_ratings_enabled() ) {
						$comment_form['comment_field'] = '<div class="comment-form-rating eafd-rating-select-box"><label for="rating">امتیاز شما <span class="required">*</span></label><select name="rating" id="rating" required>
							<option value="">انتخاب امتیاز...</option>
							<option value="5">عالی (۵ ستاره)</option>
							<option value="4">خوب (۴ ستاره)</option>
							<option value="3">متوسط (۳ ستاره)</option>
							<option value="2">ضعیف (۲ ستاره)</option>
							<option value="1">خیلی ضعیف (۱ ستاره)</option>
						</select></div>';
					}

					$comment_form['comment_field'] .= '<div class="comment-form-comment eafd-form-row"><label for="comment">متن نظر شما <span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="5" required placeholder="نظر و تجربه خرید خود را درباره این محصول بنویسید..."></textarea></div>';

					comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
					?>
				</div>
			</div>
		<?php else : ?>
			<p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'woocommerce' ); ?></p>
		<?php endif; ?>
	</div>
</div>
