<?php
/**
 * Main Index Fallback Template
 */
get_header();
?>

<div class="container" style="padding: 120px 1rem 40px;">
	<?php if ( have_posts() ) : ?>
		<div class="posts-grid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
			<?php while ( have_posts() ) : the_post(); ?>
				<article class="post-card" style="background:#fff; border-radius:16px; padding:20px; box-shadow:0 4px 15px rgba(0,0,0,0.05);">
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<div class="post-excerpt" style="margin: 15px 0; color:#5a6f80;">
						<?php the_excerpt(); ?>
					</div>
					<a href="<?php the_permalink(); ?>" style="color:var(--kh-orange); font-weight:bold;">ادامه مطلب &rarr;</a>
				</article>
			<?php endwhile; ?>
		</div>
	<?php else : ?>
		<p>هیچ محتوایی یافت نشد.</p>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
