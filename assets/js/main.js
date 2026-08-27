// Kish Harmony Drawer & Accordion Submenus JS
document.addEventListener('DOMContentLoaded', function() {
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const drawerMenu = document.getElementById('drawerMenu');
    const menuOverlay = document.getElementById('menuOverlay');
    const drawerClose = document.getElementById('drawerClose');

    function openDrawer() {
        if (drawerMenu) drawerMenu.classList.add('open');
        if (menuOverlay) menuOverlay.classList.add('active');
        if (hamburgerBtn) {
            hamburgerBtn.classList.add('open');
            hamburgerBtn.setAttribute('aria-expanded', 'true');
        }
    }

    function closeDrawer() {
        if (drawerMenu) drawerMenu.classList.remove('open');
        if (menuOverlay) menuOverlay.classList.remove('active');
        if (hamburgerBtn) {
            hamburgerBtn.classList.remove('open');
            hamburgerBtn.setAttribute('aria-expanded', 'false');
        }
    }

    if (hamburgerBtn) {
        hamburgerBtn.addEventListener('click', function() {
            if (drawerMenu && drawerMenu.classList.contains('open')) {
                closeDrawer();
            } else {
                openDrawer();
            }
        });
    }

    if (drawerClose) drawerClose.addEventListener('click', closeDrawer);
    if (menuOverlay) menuOverlay.addEventListener('click', closeDrawer);

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && drawerMenu && drawerMenu.classList.contains('open')) {
            closeDrawer();
        }
    });

    // Accordion Submenus in Drawer
    if (drawerMenu) {
        const submenus = drawerMenu.querySelectorAll('.menu-item-has-children > a, .page_item_has_children > a');
        submenus.forEach(function(parentLink) {
            parentLink.addEventListener('click', function(e) {
                const sub = parentLink.nextElementSibling;
                if (sub && (sub.tagName === 'UL' || sub.classList.contains('sub-menu'))) {
                    e.preventDefault();
                    sub.classList.toggle('open');
                    if (sub.style.display === 'block') {
                        sub.style.display = 'none';
                    } else {
                        sub.style.display = 'block';
                    }
                }
            });
        });
    }
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
// Multi-banner slider JS logic
document.addEventListener('DOMContentLoaded', function() {
    const bannerWrapper = document.querySelector('.banner-container-wrapper');
    if (!bannerWrapper) return;

    const slides = bannerWrapper.querySelectorAll('.slide');
    const dots = bannerWrapper.querySelectorAll('.dot');
    const autoScroll = bannerWrapper.getAttribute('data-autoscroll') === '1';
    const speed = parseInt(bannerWrapper.getAttribute('data-speed'), 10) || 5000;

    if (slides.length <= 1) return;

    let currentIndex = 0;
    let autoInterval = null;

    function goToSlide(index) {
        slides.forEach(s => s.classList.remove('active'));
        dots.forEach(d => d.classList.remove('active'));

        currentIndex = (index + slides.length) % slides.length;
        slides[currentIndex].classList.add('active');
        if (dots[currentIndex]) dots[currentIndex].classList.add('active');
    }

    dots.forEach((dot, idx) => {
        dot.addEventListener('click', () => {
            goToSlide(idx);
            resetAutoScroll();
        });
    });

    function startAutoScroll() {
        if (autoScroll) {
            autoInterval = setInterval(() => {
                goToSlide(currentIndex + 1);
            }, speed);
        }
    }

    function resetAutoScroll() {
        clearInterval(autoInterval);
        startAutoScroll();
    }

    startAutoScroll();
});
