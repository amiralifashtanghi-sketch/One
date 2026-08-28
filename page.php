<?php
/**
 * The template for displaying all pages (including WooCommerce My Account, Cart, Checkout)
 *
 * @package Kish_Harmony
 */

get_header();
?>

<main id="primary" class="site-main page-content-wrapper style-internal">
	<div class="container" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'card-page-inner' ); ?> style="background: #fff; border-radius: 16px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
				<header class="entry-header" style="margin-bottom: 25px; border-bottom: 2px solid #f0f0f0; padding-bottom: 15px;">
					<h1 class="entry-title" style="font-size: 1.8rem; color: var(--kh-primary-blue, #0B63D8); font-weight: 800; margin: 0;"><?php the_title(); ?></h1>
				</header>

				<div class="entry-content">
					<?php
					the_content();

					wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__( 'صفحات:', 'kish-harmony' ),
							'after'  => '</div>',
						)
					);
					?>
				</div>
			</article>
			<?php
		endwhile;
		?>
	</div>
</main>

<?php
get_footer();
