<?php
/**
 * 404 Page Template (Page Not Found)
 */

get_header();
?>

<main id="primary" class="site-main error-404-wrapper">
	<div class="container" style="max-width: 800px; margin: 60px auto; padding: 0 20px; text-align: center;">
		<div style="background: #fff; border-radius: 20px; padding: 50px 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.06);">
			<div style="font-size: 5rem; line-height: 1; margin-bottom: 20px;">🏝️ 404</div>
			<h1 style="font-size: 2.2rem; color: var(--kh-primary-blue, #0B63D8); font-weight: 800; margin-bottom: 15px;">صفحه مورد نظر پیدا نشد!</h1>
			<p style="color: #64748b; font-size: 1.15rem; line-height: 1.8; margin-bottom: 30px;">
				صفحه‌ای که به دنبال آن هستید وجود ندارد، آدرس آن تغییر کرده یا حذف شده است.
			</p>

			<div style="max-width: 500px; margin: 0 auto 30px auto;">
				<?php get_search_form(); ?>
			</div>

			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="display: inline-block; background: linear-gradient(135deg, var(--kh-primary-blue, #0B63D8) 0%, var(--kh-turquoise, #18D6D8) 100%); color: #fff; text-decoration: none; padding: 14px 30px; border-radius: 12px; font-weight: bold; font-size: 1.1rem; box-shadow: 0 6px 20px rgba(11, 99, 216, 0.25);">
				← بازگشت به صفحه اصلی کیش هارمونی
			</a>
		</div>
	</div>
</main>

<?php
get_footer();
