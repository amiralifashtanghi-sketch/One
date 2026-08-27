<?php
/**
 * Gallery Section Template with Lightbox Modal
 */
$options  = get_option( 'kish_harmony_gallery_options', array() );
$title    = ! empty( $options['title'] ) ? $options['title'] : 'گالری تصاویر کیش هارمونی';
$subtitle = ! empty( $options['subtitle'] ) ? $options['subtitle'] : 'تصاویر واقعی ثبت شده توسط مسافران و همراهان عزیز ما در کیش';
$images   = ! empty( $options['images'] ) ? $options['images'] : array();
?>

<div class="gallery-section-wrapper">
	<div class="container">
		<div class="section-title-box">
			<h2 class="gallery-title"><?php echo esc_html( $title ); ?></h2>
			<p><?php echo esc_html( $subtitle ); ?></p>
		</div>

		<div class="gallery-grid" id="galleryGrid">
			<?php foreach ( $images as $idx => $img ) : ?>
				<div class="gallery-item" data-index="<?php echo $idx; ?>">
					<img src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php echo esc_attr( $img['caption'] ); ?>" loading="lazy">
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<!-- Lightbox Modal -->
<div class="lightbox-modal" id="lightboxModal">
	<span class="lightbox-close" id="lightboxClose">&times;</span>
	<button class="lightbox-prev" id="lightboxPrev">&#10095;</button>
	<button class="lightbox-next" id="lightboxNext">&#10094;</button>
	<div class="lightbox-content">
		<img id="lightboxImg" src="" alt="">
		<div id="lightboxCaption" class="lightbox-caption"></div>
	</div>
</div>
