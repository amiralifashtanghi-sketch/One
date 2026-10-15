<?php
/**
 * Blog Articles Component (Horizontal Scroll above Footer)
 *
 * @package EAFD_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$articles_query = new WP_Query( array(
	'post_type'      => 'post',
	'posts_per_page' => 6,
	'post_status'    => 'publish',
) );

$posts_page_id   = get_option( 'page_for_posts' );
$all_posts_url   = $posts_page_id ? get_permalink( $posts_page_id ) : get_post_type_archive_link( 'post' );
if ( ! $all_posts_url ) {
	$all_posts_url = home_url( '/blog' );
}
?>

<section class="eafd-section eafd-articles-section">
	<div class="eafd-section-card">
		<div class="eafd-section-header" style="display: flex; align-items: center; justify-content: space-between;">
			<h2 class="eafd-section-title">
				<span class="eafd-title-accent"></span>
				مقالات و نوشته‌ها
			</h2>
			<a href="<?php echo esc_url( $all_posts_url ); ?>" class="eafd-view-all-link" style="font-size: 13px; font-weight: 700; color: var(--eafd-primary); display: inline-flex; align-items: center; gap: 4px;">
				مشاهده همه مقالات
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
			</a>
		</div>

		<?php if ( $articles_query->have_posts() ) : ?>
			<div class="eafd-articles-grid">
				<?php while ( $articles_query->have_posts() ) : $articles_query->the_post(); ?>
					<article class="eafd-article-card">
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
							<h3 class="eafd-article-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h3>
							<a href="<?php the_permalink(); ?>" class="eafd-article-readmore">
								ادامه مطلب
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
							</a>
						</div>
					</article>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		<?php else : ?>
			<!-- Fallback sample articles if site has no blog posts yet -->
			<div class="eafd-articles-grid">
				<?php
				$sample_articles = array(
					array( 'title' => 'فواید مصرف عسل طبیعی بر سلامتی بدنه', 'date' => '۱۴ اسفند ۱۴۰۳' ),
					array( 'title' => 'چگونه روغن های گیاهی اصل را تشخیص دهیم؟', 'date' => '۱۰ اسفند ۱۴۰۳' ),
					array( 'title' => 'خواص شگفت‌انگیز عرقیات سنتی ایرانی', 'date' => '۰۵ اسفند ۱۴۰۳' ),
					array( 'title' => 'راهنمای نگهداری زعفران صادراتی در منزل', 'date' => '۰۱ اسفند ۱۴۰۳' ),
				);
				foreach ( $sample_articles as $article ) :
				?>
					<article class="eafd-article-card">
						<div class="eafd-article-thumb-placeholder">
							<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#a0aec0" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
						</div>
						<div class="eafd-article-content">
							<span class="eafd-article-date"><?php echo esc_html( $article['date'] ); ?></span>
							<h3 class="eafd-article-title"><a href="#"><?php echo esc_html( $article['title'] ); ?></a></h3>
							<a href="#" class="eafd-article-readmore">
								ادامه مطلب
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
							</a>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
