<?php
/**
 * KishPedia Section Template (Horizontal Scrollable Layout)
 */
$args = array(
	'post_type'      => 'post',
	'posts_per_page' => 8,
);

$blog_query = new WP_Query( $args );
?>

<div class="kishpedia-wrapper">
	<div class="container">
		<div class="kishpedia-header">
			<div>
				<h2><i class="fa-solid fa-book-open"></i> کیش‌پدیا (راهنمای سفر و مقالات کیش)</h2>
				<p>آخرین اخبار، راهنماها و مقالات آموزشی جزیره کیش را بخوانید</p>
			</div>
			<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog' ) ); ?>" class="view-all-blog-btn">مشاهده همه مقالات <i class="fa-solid fa-arrow-left"></i></a>
		</div>

		<div class="horizontal-scroll-container">
			<?php if ( $blog_query->have_posts() ) : ?>
				<?php while ( $blog_query->have_posts() ) : $blog_query->the_post();
					$thumb = get_the_post_thumbnail_url( get_the_ID(), 'medium_large' );
					if ( ! $thumb ) {
						$thumb = 'https://via.placeholder.com/400x250?text=KishPedia';
					}
				?>
					<article class="kishpedia-card horizontal-card">
						<div class="kishpedia-thumb-box">
							<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>">
							<span class="kishpedia-date"><?php echo get_the_date( 'j F Y' ); ?></span>
						</div>
						<div class="kishpedia-card-body">
							<h3 class="kishpedia-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<p class="kishpedia-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 18, '...' ); ?></p>
							<a href="<?php the_permalink(); ?>" class="read-more-link">ادامه مطلب <i class="fa-solid fa-angle-left"></i></a>
						</div>
					</article>
				<?php endwhile; wp_reset_postdata(); ?>
			<?php else : ?>
				<p>هنوز مقاله‌ای منتشر نشده است.</p>
			<?php endif; ?>
		</div>
	</div>
</div>
