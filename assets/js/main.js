// Kish Harmony Header & Floating Logo Animation JS Logic
document.addEventListener('DOMContentLoaded', function() {
    const header = document.getElementById('header');
    const banner = document.getElementById('banner');
    const floatingLogo = document.getElementById('floatingLogo');

    if (!header || !banner || !floatingLogo) return;

    const logoIcon = floatingLogo.querySelector('.logo-icon');
    const logoText = floatingLogo.querySelector('.logo-text');

    const LARGE_ICON_SIZE = 56;
    const SMALL_ICON_SIZE = 36;
    const LARGE_TEXT_SIZE_REM = 2.2;
    const SMALL_TEXT_SIZE_REM = 1.3;

    let bannerCenterY = 0;
    let headerCenterY = 0;

    function updatePositions() {
        const bannerRect = banner.getBoundingClientRect();
        bannerCenterY = bannerRect.top + bannerRect.height / 2;

        const width = window.innerWidth;
        let scrolledTop, scrolledHeight;
        if (width <= 480) {
            scrolledTop = 6;
            scrolledHeight = 52;
        } else if (width <= 768) {
            scrolledTop = 8;
            scrolledHeight = 52;
        } else {
            scrolledTop = 14;
            scrolledHeight = 58;
        }
        headerCenterY = scrolledTop + scrolledHeight / 2;

        if (window.scrollY <= 10) {
            floatingLogo.style.top = bannerCenterY + 'px';
        }
    }

    function easeInOutCubic(t) {
        return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
    }

    function getScrollProgress() {
        const maxScroll = 200;
        const raw = Math.min(window.scrollY / maxScroll, 1.0);
        return easeInOutCubic(raw);
    }

    function updateLogo(progress) {
        const currentY = bannerCenterY + (headerCenterY - bannerCenterY) * progress;
        floatingLogo.style.top = currentY + 'px';

        const iconSize = LARGE_ICON_SIZE + (SMALL_ICON_SIZE - LARGE_ICON_SIZE) * progress;
        const textSizeRem = LARGE_TEXT_SIZE_REM + (SMALL_TEXT_SIZE_REM - LARGE_TEXT_SIZE_REM) * progress;

        if (logoIcon) {
            logoIcon.style.width = iconSize + 'px';
            logoIcon.style.height = iconSize + 'px';
            logoIcon.style.fontSize = (iconSize * 0.55) + 'px';
            const borderRadius = 18 + (12 - 18) * progress;
            logoIcon.style.borderRadius = borderRadius + 'px';
        }

        if (logoText) {
            logoText.style.fontSize = textSizeRem + 'rem';
        }

        floatingLogo.style.color = progress > 0.8 ? '#1e1e2f' : 'white';
    }

    let ticking = false;

    function onScroll() {
        if (window.scrollY > 10) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }

        if (!ticking) {
            requestAnimationFrame(() => {
                const progress = getScrollProgress();
                updateLogo(progress);
                ticking = false;
            });
            ticking = true;
        }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', () => {
        updatePositions();
        const progress = getScrollProgress();
        updateLogo(progress);
    });

    updatePositions();
    updateLogo(0);
    window.addEventListener('load', () => {
        updatePositions();
        updateLogo(getScrollProgress());
    });

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
