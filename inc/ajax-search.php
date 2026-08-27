<?php
/**
 * AJAX Live Search Handler & Options
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Admin Settings Page Callback
 */
function kish_harmony_search_settings_page() {
	if ( isset( $_POST['kish_harmony_save_search'] ) && check_admin_referer( 'kish_harmony_search_nonce' ) ) {
		$search_data = array(
			'placeholder'   => sanitize_text_field( $_POST['placeholder'] ?? '' ),
			'title'         => sanitize_text_field( $_POST['title'] ?? '' ),
			'subtitle'      => sanitize_text_field( $_POST['subtitle'] ?? '' ),
			'max_results'   => intval( $_POST['max_results'] ?? 6 ),
		);

		update_option( 'kish_harmony_search_options', $search_data );
		echo '<div class="updated"><p>تنظیمات جستجو با موفقیت ذخیره شد.</p></div>';
	}

	$options = get_option( 'kish_harmony_search_options', array(
		'placeholder' => 'جستجوی تفریحات، تورها و خدمات کیش...',
		'title'       => 'جستجوی هوشمند تفریحات و خدمات',
		'subtitle'    => 'نام تفریح یا کالا را تایپ کنید تا بلافاصله نتیجه آن را ببینید',
		'max_results' => 6,
	) );
	?>
	<div class="wrap">
		<h1>تنظیمات کادر جستجوی زنده (AJAX Search)</h1>
		<form method="post" action="">
			<?php wp_nonce_field( 'kish_harmony_search_nonce' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row">عنوان بخش جستجو:</th>
					<td>
						<input type="text" name="title" value="<?php echo esc_attr( $options['title'] ); ?>" class="large-text">
					</td>
				</tr>
				<tr>
					<th scope="row">متن زیرعنوان:</th>
					<td>
						<input type="text" name="subtitle" value="<?php echo esc_attr( $options['subtitle'] ); ?>" class="large-text">
					</td>
				</tr>
				<tr>
					<th scope="row">متن داخل کادر (Placeholder):</th>
					<td>
						<input type="text" name="placeholder" value="<?php echo esc_attr( $options['placeholder'] ); ?>" class="large-text">
					</td>
				</tr>
				<tr>
					<th scope="row">حداکثر تعداد نتایج زنده:</th>
					<td>
						<input type="number" name="max_results" value="<?php echo esc_attr( $options['max_results'] ); ?>" class="small-text">
					</td>
				</tr>
			</table>
			<p class="submit">
				<input type="submit" name="kish_harmony_save_search" class="button button-primary" value="ذخیره تنظیمات جستجو">
			</p>
		</form>
	</div>
	<?php
}

/**
 * AJAX Search Endpoint
 */
function kish_harmony_ajax_search() {
	check_ajax_referer( 'kish_harmony_ajax_nonce', 'nonce' );

	$query = sanitize_text_field( $_POST['query'] ?? '' );
	if ( empty( $query ) ) {
		wp_send_json_success( array( 'html' => '' ) );
	}

	$options = get_option( 'kish_harmony_search_options', array( 'max_results' => 6 ) );
	$max_results = ! empty( $options['max_results'] ) ? $options['max_results'] : 6;

	$args = array(
		'post_type'      => array( 'product', 'car_rental' ),
		'post_status'    => 'publish',
		'posts_per_page' => $max_results,
		's'              => $query,
	);

	$search_query = new WP_Query( $args );
	ob_start();

	if ( $search_query->have_posts() ) {
		echo '<ul class="ajax-search-results-list">';
		while ( $search_query->have_posts() ) {
			$search_query->the_post();
			$thumb = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
			if ( ! $thumb ) {
				$thumb = 'https://via.placeholder.com/60x60?text=Kish';
			}

			$price = '';
			if ( get_post_type() === 'product' && function_exists( 'wc_get_product' ) ) {
				$product = wc_get_product( get_the_ID() );
				if ( $product ) {
					$price = $product->get_price_html();
				}
			} elseif ( get_post_type() === 'car_rental' ) {
				$car_price = get_post_meta( get_the_ID(), '_car_price', true );
				if ( $car_price ) {
					$price = number_format( intval( $car_price ) ) . ' تومان / روزانه';
				}
			}
			?>
			<li class="ajax-search-item">
				<a href="<?php the_permalink(); ?>">
					<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>">
					<div class="search-item-info">
						<span class="search-item-title"><?php the_title(); ?></span>
						<?php if ( ! empty( $price ) ) : ?>
							<span class="search-item-price"><?php echo $price; ?></span>
						<?php endif; ?>
					</div>
				</a>
			</li>
			<?php
		}
		echo '</ul>';
		wp_reset_postdata();
	} else {
		echo '<div class="ajax-search-no-results">هیچ نتیجه‌ای یافت نشد.</div>';
	}

	$html = ob_get_clean();
	wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_kish_harmony_ajax_search', 'kish_harmony_ajax_search' );
add_action( 'wp_ajax_nopriv_kish_harmony_ajax_search', 'kish_harmony_ajax_search' );
