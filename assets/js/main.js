// Kish Harmony Combined JS Logic
document.addEventListener('DOMContentLoaded', function() {
    // 1. Floating Logo & Glassmorphism Header Animation
    const header = document.getElementById('header');
    const banner = document.getElementById('banner');
    const floatingLogo = document.getElementById('floatingLogo');

    if (header && banner && floatingLogo) {
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
    }

    // 2. Mobile Drawer Navigation & Accordion
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

    if (drawerMenu) {
        const submenus = drawerMenu.querySelectorAll('.menu-item-has-children > a, .page_item_has_children > a');
        submenus.forEach(function(parentLink) {
            parentLink.addEventListener('click', function(e) {
                const sub = parentLink.nextElementSibling;
                if (sub && (sub.tagName === 'UL' || sub.classList.contains('sub-menu'))) {
                    e.preventDefault();
                    sub.classList.toggle('open');
                    sub.style.display = sub.classList.contains('open') ? 'block' : 'none';
                }
            });
        });
    }

    // 3. Multi-Banner Slider Logic
    const sliderContainer = document.getElementById('sliderContainer');
    const sliderTrack = document.getElementById('bannerSliderTrack');
    if (sliderContainer && sliderTrack) {
        const slides = sliderTrack.querySelectorAll('.slide');
        const dots = sliderContainer.querySelectorAll('.dot');
        const prevBtn = document.getElementById('sliderPrev');
        const nextBtn = document.getElementById('sliderNext');

        const totalSlides = slides.length;
        if (totalSlides > 1) {
            let currentIndex = 0;
            let autoSlideInterval = null;
            const autoScroll = sliderContainer.getAttribute('data-autoscroll') === '1';
            const speed = parseInt(sliderContainer.getAttribute('data-speed'), 10) || 4000;

            function goToSlide(index) {
                if (index < 0) index = totalSlides - 1;
                if (index >= totalSlides) index = 0;

                currentIndex = index;
                sliderTrack.style.transform = `translateX(-${currentIndex * 100}%)`;

                dots.forEach((dot, idx) => {
                    dot.classList.toggle('active', idx === currentIndex);
                });
            }

            function nextSlide() { goToSlide(currentIndex + 1); }
            function prevSlide() { goToSlide(currentIndex - 1); }

            function startAutoSlide() {
                if (autoScroll && !autoSlideInterval) {
                    autoSlideInterval = setInterval(nextSlide, speed);
                }
            }

            function stopAutoSlide() {
                if (autoSlideInterval) {
                    clearInterval(autoSlideInterval);
                    autoSlideInterval = null;
                }
            }

            function resetAutoSlide() {
                stopAutoSlide();
                startAutoSlide();
            }

            if (nextBtn) nextBtn.addEventListener('click', () => { nextSlide(); resetAutoSlide(); });
            if (prevBtn) prevBtn.addEventListener('click', () => { prevSlide(); resetAutoSlide(); });

            dots.forEach((dot, idx) => {
                dot.addEventListener('click', () => { goToSlide(idx); resetAutoSlide(); });
            });

            sliderContainer.addEventListener('mouseenter', stopAutoSlide);
            sliderContainer.addEventListener('mouseleave', startAutoSlide);

            document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowLeft') { nextSlide(); resetAutoSlide(); }
                else if (e.key === 'ArrowRight') { prevSlide(); resetAutoSlide(); }
            });

            startAutoSlide();
        }
    }

    // 4. AJAX Live Search Logic
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

    // 5. Gallery Lightbox Modal Logic
    const galleryItems = document.querySelectorAll('.gallery-item');
    const lightboxModal = document.getElementById('lightboxModal');
    const lightboxImg = document.getElementById('lightboxImg');
    const lightboxCaption = document.getElementById('lightboxCaption');
    const lightboxClose = document.getElementById('lightboxClose');
    const lightboxPrev = document.getElementById('lightboxPrev');
    const lightboxNext = document.getElementById('lightboxNext');

    if (galleryItems.length && lightboxModal) {
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
    }

    // 6. Car Rental Drag-to-Scroll & Navigation Buttons Logic
    const scroller = document.getElementById('scroller');
    const scrollRightBtn = document.getElementById('scrollRight');
    const scrollLeftBtn = document.getElementById('scrollLeft');

    if (scroller) {
        if (scrollRightBtn) {
            scrollRightBtn.addEventListener('click', () => {
                scroller.scrollBy({ left: 1000, behavior: 'smooth' });
            });
        }
        if (scrollLeftBtn) {
            scrollLeftBtn.addEventListener('click', () => {
                scroller.scrollBy({ left: -1000, behavior: 'smooth' });
            });
        }

        let isDown = false;
        let startX;
        let scrollLeftPos;

        scroller.addEventListener('mousedown', (e) => {
            isDown = true;
            scroller.style.cursor = 'grabbing';
            startX = e.pageX - scroller.offsetLeft;
            scrollLeftPos = scroller.scrollLeft;
        });

        scroller.addEventListener('mouseleave', () => {
            isDown = false;
            scroller.style.cursor = 'grab';
        });

        scroller.addEventListener('mouseup', () => {
            isDown = false;
            scroller.style.cursor = 'grab';
        });

        scroller.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - scroller.offsetLeft;
            const walk = (x - startX) * 1.5;
            scroller.scrollLeft = scrollLeftPos - walk;
        });
    }
});
