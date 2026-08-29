<?php
/**
 * Single Product Template for WooCommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main single-product-wrapper">
	<div class="container" style="max-width: 1150px; margin: 40px auto; padding: 0 20px;">
		<?php
		while ( have_posts() ) :
			the_post();
			global $product;
			$product_id = get_the_ID();
			$discount   = get_post_meta( $product_id, '_special_discount_percent', true );
			$capacity   = get_post_meta( $product_id, '_special_capacity', true );
		?>
			<div style="background: #fff; border-radius: 20px; padding: 35px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); display: flex; flex-wrap: wrap; gap: 40px;">
				<div style="flex: 1 1 450px; position: relative;">
					<?php if ( has_post_thumbnail() ) : ?>
						<img src="<?php the_post_thumbnail_url( 'large' ); ?>" alt="<?php the_title_attribute(); ?>" style="width: 100%; border-radius: 16px; object-fit: cover;">
					<?php else : ?>
						<img src="https://via.placeholder.com/600x400?text=Kish+Product" alt="Product Image" style="width: 100%; border-radius: 16px;">
					<?php endif; ?>

					<?php if ( ! empty( $discount ) ) : ?>
						<div style="position: absolute; top: 15px; right: 15px; background: var(--kh-orange, #FF8A00); color: #fff; font-weight: bold; padding: 8px 16px; border-radius: 50px; font-size: 1.1rem;">
							🔥 <?php echo esc_html( $discount ); ?>٪ تخفیف ویژه
						</div>
					<?php endif; ?>
				</div>

				<div style="flex: 1 1 480px; display: flex; flex-direction: column; justify-content: space-between;">
					<div>
						<h1 style="font-size: 2.2rem; color: var(--kh-primary-blue, #0B63D8); font-weight: 800; margin-top: 0;"><?php the_title(); ?></h1>

						<?php if ( ! empty( $capacity ) ) : ?>
							<div style="background: #fff7ed; border: 1px solid #ffedd5; padding: 12px 18px; border-radius: 12px; margin: 15px 0; color: #c2410c; font-weight: bold; display: inline-block;">
								⏳ ظرفیت باقیمانده: <?php echo esc_html( $capacity ); ?> سانس / نفر
							</div>
						<?php endif; ?>

						<div class="product-description" style="margin: 20px 0; line-height: 1.8; color: #475569;">
							<?php the_content(); ?>
						</div>

						<div class="product-meta-taxonomies" style="margin-top: 20px; padding-top: 15px; border-top: 1px dashed #e2e8f0; font-size: 0.9rem; color: #64748b;">
							<div style="margin-bottom: 6px;"><?php echo get_the_term_list( $product_id, 'product_cat', '📁 <strong>دسته‌بندی‌ها:</strong> ', '، ' ); ?></div>
							<div><?php echo get_the_term_list( $product_id, 'product_tag', '🏷️ <strong>برچسب‌ها:</strong> ', '، ' ); ?></div>
						</div>
					</div>

					<div style="background: #f8fafc; padding: 25px; border-radius: 16px; border: 1px solid #e2e8f0;">
						<div style="margin-bottom: 20px;">
							<?php if ( $product ) : ?>
								<div style="font-size: 1.8rem; color: var(--kh-orange, #FF8A00); font-weight: 800;">
									<?php echo $product->get_price_html(); ?>
								</div>
							<?php endif; ?>
						</div>

						<?php
						$is_recreation = get_post_meta( $product_id, '_is_recreation', true );
						$rec_terms     = get_post_meta( $product_id, '_recreation_terms', true );
						$btn_text      = get_option( 'kish_harmony_add_to_cart_btn_text', '🛒 افزودن به سبد خرید' );
						?>

						<form class="custom-cart-quantity-form" id="productCartForm" method="post" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 15px;">
							<?php if ( $product && $product->is_type( 'variable' ) ) :
								$available_variations = $product->get_available_variations();
								$attributes           = $product->get_variation_attributes();
							?>
								<div class="product-variations-wrapper" style="background: #f8fafc; padding: 18px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #e2e8f0;">
									<h3 style="font-size: 1.1rem; margin-top: 0; color: var(--kh-primary-blue, #0B63D8); font-weight: 800;">⚙️ انتخاب گزینه‌ها و مشخصات:</h3>
									<?php foreach ( $attributes as $attribute_name => $options ) : ?>
										<div style="margin-bottom: 12px;">
											<label style="font-weight: bold; display: block; margin-bottom: 6px;"><?php echo wc_attribute_label( $attribute_name ); ?>:</label>
											<select name="attribute_<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>" class="variation-selector" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 1rem;">
												<option value="">-- لطفاً انتخاب کنید --</option>
												<?php foreach ( $options as $option ) : ?>
													<option value="<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $option ); ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									<?php endforeach; ?>
									<input type="hidden" name="variation_id" class="variation_id" id="variation_id" value="0">
									<div id="variationDescription" style="display: none; background: #fff; padding: 12px; border-radius: 8px; border-right: 4px solid var(--kh-orange, #FF8A00); margin-top: 10px; font-size: 0.95rem; color: #334155;"></div>
								</div>
								<script>
								const availableVariations = <?php echo wp_json_encode( $available_variations ); ?>;
								document.addEventListener('DOMContentLoaded', function() {
									const selectors = document.querySelectorAll('.variation-selector');
									const descBox = document.getElementById('variationDescription');
									const varIdInput = document.getElementById('variation_id');

									function matchVariation() {
										let selected = {};
										let allChosen = true;
										selectors.forEach(s => {
											const attrName = s.name;
											const val = s.value;
											if (!val) allChosen = false;
											selected[attrName] = val;
										});

										if (!allChosen) {
											if (descBox) descBox.style.display = 'none';
											if (varIdInput) varIdInput.value = '0';
											return;
										}

										const matched = availableVariations.find(v => {
											return Object.keys(v.attributes).every(attrKey => {
												const val = v.attributes[attrKey];
												return !val || val === selected[attrKey] || val === selected['attribute_' + attrKey];
											});
										});

										if (matched) {
											if (varIdInput) varIdInput.value = matched.variation_id;
											if (descBox) {
												if (matched.variation_description) {
													descBox.innerHTML = matched.variation_description;
													descBox.style.display = 'block';
												} else {
													descBox.style.display = 'none';
												}
											}
										}
									}

									selectors.forEach(s => s.addEventListener('change', matchVariation));
								});
								</script>
							<?php endif; ?>
							<?php if ( '1' === $is_recreation ) : ?>
								<div style="background: #eef2ff; padding: 15px; border-radius: 12px; border: 1px solid #c7d2fe;">
									<label style="font-weight: bold; display: block; margin-bottom: 6px; color: #1e1b4b;">📅 تاریخ حضور و استفاده از تفریح:</label>
									<input type="date" name="recreation_date" required min="<?php echo date( 'Y-m-d' ); ?>" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 1rem;">
								</div>
							<?php endif; ?>

							<div style="display: flex; gap: 15px; align-items: center;">
								<div class="quantity-picker-wrapper" style="display: flex; align-items: center; background: #fff; border: 2px solid var(--kh-primary-blue, #0B63D8); border-radius: 12px; overflow: hidden;">
									<button type="button" class="qty-btn minus-btn" style="width: 40px; height: 45px; border: none; background: #f1f5f9; font-size: 1.3rem; font-weight: bold; cursor: pointer;">-</button>
									<input type="number" name="quantity" value="1" min="1" max="99" style="width: 50px; text-align: center; border: none; font-size: 1.1rem; font-weight: bold;">
									<button type="button" class="qty-btn plus-btn" style="width: 40px; height: 45px; border: none; background: #f1f5f9; font-size: 1.3rem; font-weight: bold; cursor: pointer;">+</button>
								</div>

								<button type="<?php echo ! empty( $rec_terms ) ? 'button' : 'submit'; ?>" id="mainAddToCartBtn" name="add-to-cart" value="<?php echo esc_attr( $product_id ); ?>" class="single_add_to_cart_button button alt" style="flex: 1; background: linear-gradient(135deg, var(--kh-orange, #FF8A00) 0%, #e67e00 100%); color: #fff; border: none; padding: 14px; border-radius: 12px; font-size: 1.15rem; font-weight: bold; cursor: pointer;">
									<?php echo esc_html( $btn_text ); ?>
								</button>
							</div>
						</form>

						<?php if ( ! empty( $rec_terms ) ) : ?>
							<!-- Terms Modal Popup -->
							<div id="termsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 99999; justify-content: center; align-items: center;">
								<div style="background: #fff; padding: 30px; border-radius: 16px; max-width: 550px; width: 90%; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
									<h3 style="margin-top: 0; color: var(--kh-primary-blue, #0B63D8); font-weight: 800;">📜 قوانین و مقررات استفاده از این تفریح</h3>
									<div style="max-height: 200px; overflow-y: auto; background: #f8fafc; padding: 15px; border-radius: 10px; margin: 15px 0; line-height: 1.8; color: #334155;">
										<?php echo nl2br( esc_html( $rec_terms ) ); ?>
									</div>
									<label style="display: flex; gap: 10px; align-items: center; margin-bottom: 20px; font-weight: bold; color: #1e293b;">
										<input type="checkbox" id="acceptTermsCheckbox"> قوانین و مقررات فوق را مطالعه کرده و می‌پذیرم.
									</label>
									<div style="display: flex; gap: 10px; justify-content: flex-end;">
										<button type="button" id="closeTermsModal" class="button" style="padding: 10px 20px;">انصراف</button>
										<button type="button" id="confirmAddToCartBtn" disabled style="background: var(--kh-orange, #FF8A00); color: #fff; border: none; padding: 10px 25px; border-radius: 8px; font-weight: bold; opacity: 0.5; cursor: not-allowed;">تایید و افزودن به سبد خرید</button>
									</div>
								</div>
							</div>

							<script>
							document.addEventListener('DOMContentLoaded', function() {
								const mainBtn = document.getElementById('mainAddToCartBtn');
								const modal = document.getElementById('termsModal');
								const checkbox = document.getElementById('acceptTermsCheckbox');
								const confirmBtn = document.getElementById('confirmAddToCartBtn');
								const closeBtn = document.getElementById('closeTermsModal');
								const form = document.getElementById('productCartForm');

								if (mainBtn && modal) {
									mainBtn.addEventListener('click', function(e) {
										if (!form.checkValidity()) {
											form.reportValidity();
											return;
										}
										modal.style.display = 'flex';
									});
									closeBtn.addEventListener('click', function() {
										modal.style.display = 'none';
									});
									checkbox.addEventListener('change', function() {
										if (this.checked) {
											confirmBtn.disabled = false;
											confirmBtn.style.opacity = '1';
											confirmBtn.style.cursor = 'pointer';
										} else {
											confirmBtn.disabled = true;
											confirmBtn.style.opacity = '0.5';
											confirmBtn.style.cursor = 'not-allowed';
										}
									});
									confirmBtn.addEventListener('click', function() {
										if (checkbox.checked) {
											const hiddenSubmit = document.createElement('input');
											hiddenSubmit.type = 'hidden';
											hiddenSubmit.name = 'add-to-cart';
											hiddenSubmit.value = '<?php echo esc_js( $product_id ); ?>';
											form.appendChild(hiddenSubmit);
											form.submit();
										}
									});
								}
							});
							</script>
						<?php endif; ?>

						<script>
						document.addEventListener('DOMContentLoaded', function() {
							const form = document.querySelector('.custom-cart-quantity-form');
							if (form) {
								const input = form.querySelector('input[name="quantity"]');
								form.querySelector('.minus-btn').addEventListener('click', function() {
									let val = parseInt(input.value) || 1;
									if (val > 1) input.value = val - 1;
								});
								form.querySelector('.plus-btn').addEventListener('click', function() {
									let val = parseInt(input.value) || 1;
									input.value = val + 1;
								});
							}
						});
						</script>
					</div>
				</div>
			</div>

			<!-- Related Products Section -->
			<?php
			$terms = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'ids' ) );
			if ( ! empty( $terms ) ) :
				$related_query = new WP_Query( array(
					'post_type'      => 'product',
					'posts_per_page' => 4,
					'post__not_in'   => array( $product_id ),
					'tax_query'      => array(
						array(
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => $terms,
						),
					),
				) );

				if ( $related_query->have_posts() ) :
			?>
				<div style="margin-top: 50px;">
					<h2 style="font-size: 1.6rem; color: var(--kh-primary-blue, #0B63D8); font-weight: 800; margin-bottom: 25px;">🎯 پیشنهادهای مشابه و تفریحات مرتبط</h2>
					<div style="display: flex; gap: 20px; overflow-x: auto; padding-bottom: 15px; scroll-snap-type: x mandatory;">
						<?php while ( $related_query->have_posts() ) : $related_query->the_post();
							$rel_id = get_the_ID();
							$rel_thumb = get_the_post_thumbnail_url( $rel_id, 'medium' ) ?: 'https://via.placeholder.com/300x200';
						?>
							<div style="flex: 0 0 260px; scroll-snap-align: start; background: #fff; border-radius: 14px; overflow: hidden; box-shadow: 0 8px 20px rgba(0,0,0,0.04); display: flex; flex-direction: column; justify-content: space-between;">
								<div>
									<img src="<?php echo esc_url( $rel_thumb ); ?>" alt="<?php the_title_attribute(); ?>" style="width: 100%; height: 160px; object-fit: cover;">
									<div style="padding: 15px;">
										<h3 style="font-size: 1.1rem; margin: 0 0 8px 0; color: #1e293b; font-weight: 700;"><?php the_title(); ?></h3>
									</div>
								</div>
								<div style="padding: 0 15px 15px 15px;">
									<a href="<?php the_permalink(); ?>" style="display: block; width: 100%; text-align: center; background: #f1f5f9; color: var(--kh-primary-blue, #0B63D8); text-decoration: none; padding: 10px; border-radius: 8px; font-weight: bold; font-size: 0.95rem;">مشاهده ←</a>
								</div>
							</div>
						<?php endwhile; wp_reset_postdata(); ?>
					</div>
				</div>
			<?php endif; endif; ?>
		<?php endwhile; ?>
	</div>
</main>

<?php
get_footer();
