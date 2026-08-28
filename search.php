<?php
/**
 * Search Results Template
 */

get_header();
?>

<main id="primary" class="site-main search-results-wrapper">
	<div class="container" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
		<div style="text-align: center; margin-bottom: 40px;">
			<h1 style="font-size: 2rem; color: var(--kh-primary-blue, #0B63D8); font-weight: 800;">
				🔍 نتایج جستجو برای: «<?php echo esc_html( get_search_query() ); ?>»
			</h1>
		</div>

		<?php if ( have_posts() ) : ?>
			<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
				<?php
				while ( have_posts() ) :
					the_post();
					$thumb = get_the_post_thumbnail_url( get_the_ID(), 'medium' ) ?: 'https://via.placeholder.com/400x250?text=Kish+Harmony';
				?>
					<div style="background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); display: flex; flex-direction: column; justify-content: space-between;">
						<div>
							<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>" style="width: 100%; height: 180px; object-fit: cover;">
							<div style="padding: 20px;">
								<span style="font-size: 0.8rem; background: #e2e8f0; padding: 4px 10px; border-radius: 20px; font-weight: bold; color: #475569;"><?php echo get_post_type(); ?></span>
								<h3 style="font-size: 1.2rem; margin: 10px 0; color: #1e293b; font-weight: 800;"><?php the_title(); ?></h3>
							</div>
						</div>
						<div style="padding: 0 20px 20px 20px;">
							<a href="<?php the_permalink(); ?>" style="display: block; width: 100%; text-align: center; background: var(--kh-primary-blue, #0B63D8); color: #fff; text-decoration: none; padding: 10px; border-radius: 10px; font-weight: bold;">مشاهده جزئیات</a>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
			<div style="margin-top: 40px; text-align: center;">
				<?php the_posts_pagination(); ?>
			</div>
		<?php else : ?>
			<p style="text-align: center;">موردی متناسب با جستجوی شما یافت نشد.</p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
