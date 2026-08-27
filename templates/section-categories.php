<?php
/**
 * Categories Section Template (Kish Entertainment Categories)
 */
$options  = get_option( 'kish_harmony_categories_options', array() );
$title    = ! empty( $options['section_title'] ) ? $options['section_title'] : 'دسته‌بندی‌های تفریحات کیش';
$subtitle = ! empty( $options['section_subtitle'] ) ? $options['section_subtitle'] : 'از بین محبوب‌ترین برنامه‌های تفریحی جزیره کیش، انتخاب خود را انجام دهید';
$items    = ! empty( $options['items'] ) ? $options['items'] : array();
?>

<div class="entertainment-categories-wrapper">
	<div class="container">
		<div class="section-title-box">
			<h2><?php echo esc_html( $title ); ?></h2>
			<p><?php echo esc_html( $subtitle ); ?></p>
		</div>

		<div class="grid-soft">
			<?php foreach ( $items as $item ) :
				$link = '#';
				if ( ! empty( $item['cat_id'] ) && taxonomy_exists( 'product_cat' ) ) {
					$term_link = get_term_link( intval( $item['cat_id'] ), 'product_cat' );
					if ( ! is_wp_error( $term_link ) ) {
						$link = $term_link;
					}
				} elseif ( ! empty( $item['custom_link'] ) ) {
					$link = $item['custom_link'];
				}
				$icon = ! empty( $item['icon'] ) ? $item['icon'] : 'fa-ship';
			?>
				<a href="<?php echo esc_url( $link ); ?>" class="card-soft">
					<?php if ( ! empty( $item['image_url'] ) ) : ?>
						<img src="<?php echo esc_url( $item['image_url'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" class="card-soft-img">
					<?php else : ?>
						<i class="fa-solid <?php echo esc_attr( $icon ); ?>"></i>
					<?php endif; ?>
					<div class="card-label"><?php echo esc_html( $item['title'] ); ?></div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</div>
