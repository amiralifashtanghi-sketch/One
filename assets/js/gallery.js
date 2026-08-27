// Gallery Lightbox Modal Logic
document.addEventListener('DOMContentLoaded', function() {
    const galleryItems = document.querySelectorAll('.gallery-item');
    const lightboxModal = document.getElementById('lightboxModal');
    const lightboxImg = document.getElementById('lightboxImg');
    const lightboxCaption = document.getElementById('lightboxCaption');
    const lightboxClose = document.getElementById('lightboxClose');
    const lightboxPrev = document.getElementById('lightboxPrev');
    const lightboxNext = document.getElementById('lightboxNext');

    if (!galleryItems.length || !lightboxModal) return;

    let currentIndex = 0;
    const imagesList = [];

    galleryItems.forEach((item, idx) => {
        const img = item.querySelector('img');
        if (img) {
            imagesList.push({
                src: img.src,
                caption: img.alt || ''
            });

            item.addEventListener('click', function() {
                currentIndex = idx;
                openLightbox(currentIndex);
            });
        }
    });

    function openLightbox(index) {
        if (imagesList[index]) {
            lightboxImg.src = imagesList[index].src;
            lightboxCaption.textContent = imagesList[index].caption;
            lightboxModal.classList.add('active');
        }
    }

    function closeLightbox() {
        lightboxModal.classList.remove('active');
    }

    if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);

    if (lightboxPrev) {
        lightboxPrev.addEventListener('click', function() {
            currentIndex = (currentIndex + 1) % imagesList.length;
            openLightbox(currentIndex);
        });
    }

    if (lightboxNext) {
        lightboxNext.addEventListener('click', function() {
            currentIndex = (currentIndex - 1 + imagesList.length) % imagesList.length;
            openLightbox(currentIndex);
        });
    }

    document.addEventListener('keydown', function(e) {
        if (!lightboxModal.classList.contains('active')) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowRight') lightboxPrev.click();
        if (e.key === 'ArrowLeft') lightboxNext.click();
    });
});
