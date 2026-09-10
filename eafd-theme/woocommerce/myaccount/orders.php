<?php
/**
 * My Account Orders
 *
 * @package EAFD_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<h2 class="eafd-account-section-title">سفارش‌های من</h2>

<?php if ( $has_orders ) : ?>

	<div class="eafd-orders-card-list">
		<?php
		foreach ( $customer_orders->orders as $customer_order ) :
			$order      = wc_get_order( $customer_order );
			$item_count = $order->get_item_count();
			$status     = $order->get_status();
			$status_name = wc_get_order_status_name( $status );
			?>
			<div class="eafd-order-card">
				<div class="eafd-order-card-header">
					<div class="eafd-order-id-date">
						<span class="eafd-order-number">سفارش #<?php echo esc_html( $order->get_order_number() ); ?></span>
						<span class="eafd-order-date"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></span>
					</div>
					<span class="eafd-order-status eafd-status-<?php echo esc_attr( $status ); ?>">
						<?php echo esc_html( $status_name ); ?>
					</span>
				</div>

				<div class="eafd-order-card-body">
					<div class="eafd-order-meta-item">
						<span>تعداد اقلام:</span>
						<strong><?php echo esc_html( eafd_convert_to_persian_digits( $item_count ) ); ?> عدد</strong>
					</div>
					<div class="eafd-order-meta-item">
						<span>مبلغ کل:</span>
						<strong><?php echo $order->get_formatted_order_total(); ?></strong>
					</div>
				</div>

				<div class="eafd-order-card-footer">
					<?php
					$actions = wc_get_account_orders_actions( $order );
					if ( ! empty( $actions ) ) {
						foreach ( $actions as $key => $action ) {
							echo '<a href="' . esc_url( $action['url'] ) . '" class="eafd-btn eafd-btn-outline eafd-order-action ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
						}
					}
					?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination eafd-pagination-wrapper">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'woocommerce' ); ?></a>
			<?php endif; ?>

			<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php else : ?>
	<div class="eafd-empty-account-state">
		<div class="eafd-empty-icon">🛍️</div>
		<h3>هنوز هیچ سفارشی ثبت نکرده‌اید!</h3>
		<p>می‌توانید همین حالا از فروشگاه دیدن کنید و اولین سفارش خود را ثبت کنید.</p>
		<a class="eafd-btn eafd-btn-primary" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
			مشاهده فروشگاه
		</a>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
