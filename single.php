<?php
/**
 * Single Post Template for KishPedia Articles
 */

get_header();
?>

<main id="primary" class="site-main single-post-wrapper">
	<div class="container" style="max-width: 900px; margin: 40px auto; padding: 0 20px;">
		<?php
		while ( have_posts() ) :
			the_post();
		?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="background: #fff; border-radius: 20px; padding: 35px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
				<header style="margin-bottom: 25px; text-align: center;">
					<h1 style="font-size: 2.2rem; color: var(--kh-primary-blue, #0B63D8); font-weight: 800; line-height: 1.4; margin-bottom: 15px;"><?php the_title(); ?></h1>
					<div style="color: #64748b; font-size: 0.95rem; display: flex; justify-content: center; gap: 20px;">
						<span>📅 تاریخ انتشار: <?php echo get_the_date(); ?></span>
						<span>✍️ نویسنده: <?php the_author(); ?></span>
					</div>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<div style="margin-bottom: 30px; text-align: center;">
						<img src="<?php the_post_thumbnail_url( 'large' ); ?>" alt="<?php the_title_attribute(); ?>" style="width: 100%; border-radius: 16px; max-height: 480px; object-fit: cover;">
					</div>
				<?php endif; ?>

				<div class="entry-content" style="line-height: 2; font-size: 1.15rem; color: #334155;">
					<?php the_content(); ?>
				</div>

				<footer style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
					<div><?php the_tags( 'برچسب‌ها: ', ' ، ' ); ?></div>
					<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/' ) ); ?>" style="color: var(--kh-primary-blue, #0B63D8); font-weight: bold; text-decoration: none;">← بازگشت به کیش‌پدیا</a>
				</footer>
			</article>
		<?php endwhile; ?>
	</div>
</main>

<?php
get_footer();
