<?php
/**
 * Search Bar Component
 *
 * @package EAFD_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="eafd-search-bar-wrapper">
	<form role="search" method="get" class="eafd-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<input type="hidden" name="post_type" value="product" />

		<div class="eafd-search-input-container">
			<span class="eafd-search-icon">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<circle cx="11" cy="11" r="8"></circle>
					<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
				</svg>
			</span>
			<input type="search" class="eafd-search-input" placeholder="جستجوی محصول" value="<?php echo get_search_query(); ?>" name="s" title="جستجوی محصول" required />
		</div>

		<button type="submit" class="eafd-search-submit-btn">
			جستجو
		</button>
	</form>
</div>
