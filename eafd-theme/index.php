<?php
/**
 * Index Template
 *
 * @package EAFD_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="eafd-container">
	<div class="eafd-section-card" style="margin-top: 20px;">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) : the_post();
				the_content();
			endwhile;
		else :
			echo '<p>محتوایی یافت نشد.</p>';
		endif;
		?>
	</div>
</div>

<?php
get_footer();
