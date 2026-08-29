<?php
/**
 * Light Glassmorphic Search Section Template
 */

$options     = get_option( 'kish_harmony_search_options', array() );
$title       = ! empty( $options['title'] ) ? $options['title'] : 'جست‌وجوی سازمانی';
$subtitle    = ! empty( $options['subtitle'] ) ? $options['subtitle'] : 'جست‌وجو در اسناد، تفریحات، خودروها و منابع';
$placeholder = ! empty( $options['placeholder'] ) ? $options['placeholder'] : 'عبارت مورد نظر را وارد کنید...';
?>

<div class="search-section-outer-light">
	<div class="search-container">
		<div class="search-card-light">

			<!-- Header -->
			<div class="search-header">
				<div class="search-icon-badge">
					<svg viewBox="0 0 24 24" width="20" height="20">
						<circle cx="11" cy="11" r="7" fill="none" stroke="#ffffff" stroke-width="2"/>
						<line x1="16.5" y1="16.5" x2="21" y2="21" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
					</svg>
				</div>
				<div>
					<div class="search-title"><?php echo esc_html( $title ); ?></div>
					<div class="search-subtitle"><?php echo esc_html( $subtitle ); ?></div>
				</div>
			</div>

			<!-- Search Form Wrapper -->
			<form class="search-input-wrapper" id="searchForm" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
				<div class="input-icon">
					<svg viewBox="0 0 24 24" width="18" height="18">
						<circle cx="11" cy="11" r="7" fill="none" stroke="#64748b" stroke-width="2"/>
						<line x1="16.5" y1="16.5" x2="21" y2="21" stroke="#64748b" stroke-width="2" stroke-linecap="round"/>
					</svg>
				</div>

				<input type="text" name="s" class="search-input" id="ajaxSearchInput" placeholder="<?php echo esc_attr( $placeholder ); ?>" autocomplete="off" aria-label="جست‌وجو">
				<button type="submit" class="search-btn" id="searchSubmitBtn">
					<span class="btn-text-label">جست‌وجو</span>
					<i class="fa-solid fa-magnifying-glass btn-icon-mobile" style="display:none;"></i>
				</button>
			</form>

			<!-- Live Dropdown Results Menu (Overlaps Parent) -->
			<div class="search-results-dropdown" id="searchResultsWrapper"></div>

		</div>
	</div>
</div>
