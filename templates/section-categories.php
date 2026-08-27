<?php
/**
 * Entertainment Categories Section Template (Card-Soft Square Grid)
 */
$options  = get_option( 'kish_harmony_categories_options', array() );
$title    = ! empty( $options['section_title'] ) ? $options['section_title'] : 'دسته‌بندی‌های تفریحات کیش';
$subtitle = ! empty( $options['section_subtitle'] ) ? $options['section_subtitle'] : 'از بین محبوب‌ترین برنامه‌های تفریحی جزیره کیش، انتخاب خود را انجام دهید';
$items    = ! empty( $options['items'] ) && is_array( $options['items'] ) ? $options['items'] : array();

$default_gradients = array(
	'linear-gradient(135deg, #43C6AC, #1b8a76)',
	'linear-gradient(135deg, #F2994A, #d97a2b)',
	'linear-gradient(135deg, #667eea, #4a56b6)',
	'linear-gradient(135deg, #ee5a24, #c23a0c)',
	'linear-gradient(135deg, #11998e, #0b6b61)',
	'linear-gradient(135deg, #6a3093, #441f63)',
	'linear-gradient(135deg, #00b4db, #0077a3)',
);
?>

<div class="entertainment-categories-wrapper">
	<div class="container">
		<div class="section-title-box">
			<h2><?php echo esc_html( $title ); ?></h2>
			<p><?php echo esc_html( $subtitle ); ?></p>
		</div>

		<div class="grid-soft">
			<?php foreach ( $items as $idx => $item ) :
				$link = '#';
				if ( ! empty( $item['cat_id'] ) && taxonomy_exists( 'product_cat' ) ) {
					$term_link = get_term_link( intval( $item['cat_id'] ), 'product_cat' );
					if ( ! is_wp_error( $term_link ) ) {
						$link = $term_link;
					}
				} elseif ( ! empty( $item['custom_link'] ) ) {
					$link = $item['custom_link'];
				}

				$icon  = ! empty( $item['icon'] ) ? $item['icon'] : 'fa-ship';
				$bg_style = '';
				if ( ! empty( $item['image_url'] ) ) {
					$bg_style = "background-image: url('" . esc_url( $item['image_url'] ) . "'); background-size: cover; background-position: center;";
				} else {
					$gradient = $default_gradients[ $idx % count( $default_gradients ) ];
					$bg_style = "background: {$gradient};";
				}
			?>
				<a href="<?php echo esc_url( $link ); ?>" class="card-soft" style="<?php echo esc_attr( $bg_style ); ?>">
					<i class="fa-solid <?php echo esc_attr( $icon ); ?>"></i>
					<div class="card-label"><?php echo esc_html( $item['title'] ); ?></div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</div>
