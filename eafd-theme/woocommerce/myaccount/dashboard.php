<?php
/**
 * My Account Dashboard
 *
 * @package EAFD_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_user = wp_get_current_user();
?>

<div class="eafd-dashboard-welcome">
	<h2>خوش آمدید، <?php echo esc_html( $current_user->display_name ); ?>! 👋</h2>
	<p>از طریق پیشخوان حساب کاربری خود می‌توانید آخرین سفارش‌ها، آدرس‌های ارسال و تحویل فاکتور و اطلاعات حساب خود را مدیریت کنید.</p>
</div>

<div class="eafd-dashboard-quick-cards">
	<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" class="eafd-quick-card">
		<div class="eafd-quick-card-icon">📦</div>
		<h3>سفارش‌های من</h3>
		<p>مشاهده پیگیری و سابقه سفارش‌ها</p>
	</a>

	<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-address' ) ); ?>" class="eafd-quick-card">
		<div class="eafd-quick-card-icon">📍</div>
		<h3>آدرس‌ها</h3>
		<p>مدیریت آدرس‌های تحویل سفارش</p>
	</a>

	<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-account' ) ); ?>" class="eafd-quick-card">
		<div class="eafd-quick-card-icon">👤</div>
		<h3>اطلاعات حساب</h3>
		<p>ویرایش نام، ایمیل و رمز عبور</p>
	</a>
</div>

<?php
	/**
	 * My Account dashboard actions
	 */
	do_action( 'woocommerce_account_dashboard' );
?>
