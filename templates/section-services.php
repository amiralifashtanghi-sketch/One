<?php
/**
 * Hero Container Template (Hero Section + Standalone Quick Services Grid)
 */
$services = get_option( 'kish_harmony_services_options', array(
	array( 'title' => 'قطار', 'emoji' => '🚆', 'image_url' => '', 'page_id' => 0, 'badge' => '', 'is_special' => '0' ),
	array( 'title' => 'پرواز', 'emoji' => '✈️', 'image_url' => '', 'page_id' => 0, 'badge' => '', 'is_special' => '0' ),
	array( 'title' => 'هتل', 'emoji' => '🏨', 'image_url' => '', 'page_id' => 0, 'badge' => '', 'is_special' => '0' ),
	array( 'title' => 'اتوبوس', 'emoji' => '🚌', 'image_url' => '', 'page_id' => 0, 'badge' => '', 'is_special' => '0' ),
	array( 'title' => 'ویژه', 'emoji' => '⭐', 'image_url' => '', 'page_id' => 0, 'badge' => 'جدید', 'is_special' => '1' ),
	array( 'title' => 'ویلا و اقامتگاه', 'emoji' => '🏡', 'image_url' => '', 'page_id' => 0, 'badge' => '', 'is_special' => '0' ),
	array( 'title' => 'تور', 'emoji' => '🧳', 'image_url' => '', 'page_id' => 0, 'badge' => '', 'is_special' => '0' ),
	array( 'title' => 'نسخه جدید', 'emoji' => '✨', 'image_url' => '', 'page_id' => 0, 'badge' => '', 'is_special' => '1' ),
) );
?>

<div class="hero">
	<div class="banner" id="banner"></div>
</div>

<!-- Standalone Categories Box below Hero -->
<div class="categories-wrapper">
	<div class="categories-grid">
		<?php foreach ( $services as $item ) :
			$link  = ! empty( $item['page_id'] ) ? get_permalink( $item['page_id'] ) : '#';
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
