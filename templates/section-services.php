<?php
/**
 * Services Grid Component Template
 */
$services = get_option( 'kish_harmony_services_options', array() );
if ( empty( $services ) ) {
	return;
}
?>

<div class="categories-wrapper">
	<div class="categories-grid">
		<?php foreach ( $services as $item ) :
			$link = $item['page_id'] ? get_permalink( $item['page_id'] ) : '#';
			$class = 'category-item' . ( ! empty( $item['is_special'] ) && $item['is_special'] === '1' ? ' special' : '' );
		?>
			<a href="<?php echo esc_url( $link ); ?>" class="<?php echo esc_attr( $class ); ?>">
				<?php if ( ! empty( $item['image_url'] ) ) : ?>
					<img src="<?php echo esc_url( $item['image_url'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" style="width:24px; height:24px; object-fit:contain;">
				<?php elseif ( ! empty( $item['emoji'] ) ) : ?>
					<span class="category-emoji"><?php echo esc_html( $item['emoji'] ); ?></span>
				<?php endif; ?>

				<span class="category-text"><?php echo esc_html( $item['title'] ); ?></span>

				<?php if ( ! empty( $item['badge'] ) ) : ?>
					<span class="badge"><?php echo esc_html( $item['badge'] ); ?></span>
				<?php endif; ?>
			</a>
		<?php endforeach; ?>
	</div>
</div>
