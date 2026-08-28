<?php
/**
 * Main Index & Blog Posts Archive Template
 */

get_header();
?>

<main id="primary" class="site-main blog-index-wrapper">
	<div class="container" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
		<div style="text-align: center; margin-bottom: 40px;">
			<h1 style="font-size: 2.2rem; color: var(--kh-primary-blue, #0B63D8); font-weight: 800;">📚 کیش‌پدیا - مجله گردشگری و راهنمای سفر به کیش</h1>
			<p style="color: #64748b; font-size: 1.1rem;">جدیدترین اخبار، راهنماهای جاذبه‌های گردشگری و مقالات مفصل جزیره کیش</p>
		</div>

		<?php if ( have_posts() ) : ?>
			<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px;">
				<?php
				while ( have_posts() ) :
					the_post();
					$thumb = get_the_post_thumbnail_url( get_the_ID(), 'medium_large' ) ?: 'https://via.placeholder.com/400x250?text=Kishpedia';
				?>
					<div style="background: #fff; border-radius: 18px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); display: flex; flex-direction: column; justify-content: space-between;">
						<div>
							<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>" style="width: 100%; height: 200px; object-fit: cover;">
							<div style="padding: 22px;">
								<div style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 8px;">📅 <?php echo get_the_date(); ?></div>
								<h2 style="font-size: 1.25rem; margin: 0 0 12px 0; color: #1e293b; font-weight: 800; line-height: 1.5;"><?php the_title(); ?></h2>
								<div style="color: #64748b; font-size: 0.95rem; line-height: 1.7;">
									<?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
								</div>
							</div>
						</div>
						<div style="padding: 0 22px 22px 22px;">
							<a href="<?php the_permalink(); ?>" style="display: block; width: 100%; text-align: center; background: #f1f5f9; color: var(--kh-primary-blue, #0B63D8); text-decoration: none; padding: 12px; border-radius: 10px; font-weight: bold; transition: 0.3s;">مطالعه کامل مقاله ←</a>
						</div>
					</div>
				<?php endwhile; ?>
			</div>

			<div style="margin-top: 40px; text-align: center;">
				<?php the_posts_pagination(); ?>
			</div>
		<?php else : ?>
			<p style="text-align: center;">هنوز مقاله‌ای در کیش‌پدیا منتشر نشده است.</p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
