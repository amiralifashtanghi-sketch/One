<?php
/**
 * Single Page Template (Static WordPress Pages)
 *
 * @package EAFD_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="eafd-container">
	<div class="eafd-section-card" style="margin-top: 20px; margin-bottom: 30px;">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header" style="margin-bottom: 20px; border-bottom: 1px solid #edf2f7; padding-bottom: 12px;">
						<h1 class="entry-title" style="font-size: 22px; font-weight: 800; color: #1a202c;"><?php the_title(); ?></h1>
					</header>

					<?php if ( has_post_thumbnail() ) : ?>
						<div class="entry-thumbnail" style="margin-bottom: 20px; border-radius: 12px; overflow: hidden;">
							<?php the_post_thumbnail( 'large', array( 'style' => 'width: 100%; height: auto; display: block;' ) ); ?>
						</div>
					<?php endif; ?>

					<div class="entry-content" style="font-size: 15px; color: #2d3748; line-height: 1.8;">
						<?php the_content(); ?>
					</div>
				</article>
			<?php endwhile; ?>
		<?php else : ?>
			<p>محتوایی یافت نشد.</p>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
