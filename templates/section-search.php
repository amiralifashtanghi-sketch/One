<?php
/**
 * Glassmorphic Search Section Template (60/30/10 Corporate Palette)
 */

$options     = get_option( 'kish_harmony_search_options', array() );
$title       = ! empty( $options['title'] ) ? $options['title'] : 'جست‌وجوی سازمانی';
$subtitle    = ! empty( $options['subtitle'] ) ? $options['subtitle'] : 'جست‌وجو در تفریحات، خودروها، اسناد و خدمات کیش...';
$placeholder = ! empty( $options['placeholder'] ) ? $options['placeholder'] : 'عبارت مورد نظر را وارد کنید...';
?>

<div class="search-section-outer">
	<!-- Background Floating Glowing Orbs -->
	<div class="orb orb-1"></div>
	<div class="orb orb-2"></div>
	<div class="orb orb-3"></div>
	<div class="orb orb-4"></div>

	<div class="search-container">
		<div class="search-card">

			<!-- Header -->
			<div class="search-header">
				<div class="search-icon-badge">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="11" cy="11" r="8"></circle>
						<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
					</svg>
				</div>
				<div>
					<div class="search-title"><?php echo esc_html( $title ); ?></div>
					<div class="search-subtitle"><?php echo esc_html( $subtitle ); ?></div>
				</div>
			</div>

			<!-- Search Input Form Wrapper -->
			<form class="search-input-wrapper" id="searchForm" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
				<div class="input-icon">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="11" cy="11" r="8"></circle>
						<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
					</svg>
				</div>

				<input type="text" name="s" class="search-input" id="ajaxSearchInput" placeholder="<?php echo esc_attr( $placeholder ); ?>" autocomplete="off">
				<button type="submit" class="search-btn" id="searchSubmitBtn">جست‌وجو</button>
			</form>

			<!-- Live AJAX Search Dropdown Results Wrapper -->
			<div class="ajax-search-results-wrapper" id="searchResultsWrapper"></div>

		</div>
	</div>
</div>
