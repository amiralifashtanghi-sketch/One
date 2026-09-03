<?php
/**
 * Blog Posts Index Template (Homepage Blog / Posts Page)
 *
 * @package EAFD_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="eafd-container">
	<section class="eafd-section eafd-blog-archive-section" style="margin-top: 16px;">
		<div class="eafd-section-card">
			<div class="eafd-section-header">
				<h1 class="eafd-section-title">
					<span class="eafd-title-accent"></span>
					مقالات و نوشته‌های سایت
				</h1>
			</div>

			<?php if ( have_posts() ) : ?>
				<div class="eafd-blog-archive-grid">
					<?php while ( have_posts() ) : the_post(); ?>
						<article class="eafd-article-card eafd-blog-archive-card">
							<a href="<?php the_permalink(); ?>" class="eafd-article-thumb-link">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'medium', array( 'class' => 'eafd-article-thumb', 'loading' => 'lazy' ) ); ?>
								<?php else : ?>
									<div class="eafd-article-thumb-placeholder">
										<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#a0aec0" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
									</div>
								<?php endif; ?>
							</a>

							<div class="eafd-article-content">
								<span class="eafd-article-date"><?php echo esc_html( eafd_convert_to_persian_digits( get_the_date( 'j F Y' ) ) ); ?></span>
								<h2 class="eafd-article-title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h2>
								<p class="eafd-article-excerpt" style="font-size: 13px; color: #718096; margin-bottom: 12px; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
									<?php echo esc_html( wp_trim_words( get_the_excerpt(), 18, '...' ) ); ?>
								</p>
								<a href="<?php the_permalink(); ?>" class="eafd-article-readmore">
									ادامه مطلب
									<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
								</a>
							</div>
						</article>
					<?php endwhile; ?>
				</div>

				<div class="eafd-pagination-wrapper" style="margin-top: 24px; text-align: center;">
					<?php
					echo paginate_links( array(
						'prev_text' => '« قبلی',
						'next_text' => 'بعدی »',
						'type'      => 'list',
					) );
					?>
				</div>
			<?php else : ?>
				<p style="text-align: center; padding: 40px; color: #718096;">هنوز مقاله‌ای منتشر نشده است.</p>
			<?php endif; ?>
		</div>
	</section>
</div>

<?php
get_footer();
