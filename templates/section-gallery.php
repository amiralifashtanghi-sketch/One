<?php
/**
 * Gallery Section Template (Strict Specifications Model)
 */

$options    = get_option( 'kish_harmony_gallery_options', array() );
$title      = ! empty( $options['title'] ) ? $options['title'] : 'گالری تصاویر کیش هارمونی';
$share_text = ! empty( $options['share_text'] ) ? $options['share_text'] : '📸 عکس‌هایتان را با ما به اشتراک بگذارین';
$images     = ! empty( $options['images'] ) && is_array( $options['images'] ) ? $options['images'] : array(
	array( 'url' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=800&q=80', 'caption' => 'تفریحات دریایی کیش' ),
	array( 'url' => 'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&w=800&q=80', 'caption' => 'ساحل زیبای مرجان' ),
	array( 'url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80', 'caption' => 'غروب آفتاب کیش' ),
	array( 'url' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=800&q=80', 'caption' => 'غواصی در کیش' ),
	array( 'url' => 'https://images.unsplash.com/photo-1519046904884-53103b34b206?auto=format&fit=crop&w=800&q=80', 'caption' => 'ساحل طلایی کیش' ),
	array( 'url' => 'https://images.unsplash.com/photo-1506929562872-bb421503ef21?auto=format&fit=crop&w=800&q=80', 'caption' => 'قایق‌سواری کیش' ),
);
?>

<section class="gallery-section">
	<h2 class="gallery-title"><?php echo esc_html( $title ); ?></h2>

	<div class="gallery-grid" id="galleryGrid">
		<?php foreach ( $images as $idx => $img ) : ?>
			<div class="gallery-item">
				<img src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php echo esc_attr( $img['caption'] ?? 'تصویر کیش هارمونی' ); ?>" loading="lazy">
			</div>
		<?php endforeach; ?>
	</div>

	<p class="share-text"><?php echo esc_html( $share_text ); ?></p>
</section>

<!-- Lightbox Component -->
<div class="lightbox" id="lightbox">
	<button class="lightbox__close" id="lightboxClose">&times;</button>
	<button class="lightbox__nav lightbox__prev" id="lightboxPrev">&#8249;</button>
	<button class="lightbox__nav lightbox__next" id="lightboxNext">&#8250;</button>
	<div class="lightbox__content-wrapper">
		<img class="lightbox__image" id="lightboxImage" src="" alt="">
	</div>
	<div class="lightbox__counter" id="lightboxCounter"></div>
</div>
