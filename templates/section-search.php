<?php
/**
 * Search Section Template
 */
$options     = get_option( 'kish_harmony_search_options', array() );
$title       = ! empty( $options['title'] ) ? $options['title'] : 'جستجوی هوشمند تفریحات و خدمات';
$subtitle    = ! empty( $options['subtitle'] ) ? $options['subtitle'] : 'نام تفریح یا کالا را تایپ کنید تا بلافاصله نتیجه آن را ببینید';
$placeholder = ! empty( $options['placeholder'] ) ? $options['placeholder'] : 'جستجوی تفریحات، تورها و خدمات کیش...';
?>

<div class="search-section-wrapper">
	<div class="container">
		<div class="search-header-text">
			<h2><i class="fa-solid fa-magnifying-glass"></i> <?php echo esc_html( $title ); ?></h2>
			<p><?php echo esc_html( $subtitle ); ?></p>
		</div>

		<div class="ajax-search-box-container">
			<form class="ajax-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
				<input type="text" name="s" id="ajaxSearchInput" placeholder="<?php echo esc_attr( $placeholder ); ?>" autocomplete="off">
				<button type="submit" class="search-submit-btn"><i class="fa-solid fa-search"></i> جستجو</button>
			</form>
			<div class="ajax-search-results-wrapper" id="searchResultsWrapper"></div>
		</div>
	</div>
</div>
