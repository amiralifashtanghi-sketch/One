document.addEventListener('DOMContentLoaded', function() {
    // Header Scroll Effect
    const header = document.getElementById('header');
    function updateHeaderClass() {
        if (window.scrollY > 10) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }
    window.addEventListener('scroll', updateHeaderClass, { passive: true });
    updateHeaderClass();

    // Mobile Drawer Navigation
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const mobileDrawer = document.getElementById('mobileDrawer');
    const drawerOverlay = document.getElementById('drawerOverlay');
    const drawerClose = document.getElementById('drawerClose');

    function toggleDrawer() {
        if (mobileDrawer) {
            mobileDrawer.classList.toggle('active');
        }
    }

    if (hamburgerBtn) hamburgerBtn.addEventListener('click', toggleDrawer);
    if (drawerOverlay) drawerOverlay.addEventListener('click', toggleDrawer);
    if (drawerClose) drawerClose.addEventListener('click', toggleDrawer);
});
// AJAX Live Search JS Logic
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('ajaxSearchInput');
    const resultsWrapper = document.getElementById('searchResultsWrapper');

    if (searchInput && resultsWrapper) {
        let debounceTimer;

        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            clearTimeout(debounceTimer);

            if (query.length < 2) {
                resultsWrapper.classList.remove('active');
                resultsWrapper.innerHTML = '';
                return;
            }

            debounceTimer = setTimeout(function() {
                const formData = new FormData();
                formData.append('action', 'kish_harmony_ajax_search');
                formData.append('query', query);
                formData.append('nonce', kishHarmonyData.nonce);

                fetch(kishHarmonyData.ajaxUrl, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data.html) {
                        resultsWrapper.innerHTML = data.data.html;
                        resultsWrapper.classList.add('active');
                    } else {
                        resultsWrapper.classList.remove('active');
                    }
                })
                .catch(error => console.error('Error fetching search results:', error));
            }, 300);
        });

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !resultsWrapper.contains(e.target)) {
                resultsWrapper.classList.remove('active');
            }
        });
    }
});
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
