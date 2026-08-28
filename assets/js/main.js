// Kish Harmony Combined JS Logic
document.addEventListener('DOMContentLoaded', function() {
    // 1. Floating Logo & Glassmorphism Header Animation
    const header = document.getElementById('header');
    const banner = document.getElementById('banner');
    const floatingLogo = document.getElementById('floatingLogo');

    if (header) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 10) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }, { passive: true });
    }

    if (header && banner && floatingLogo) {
        const logoIcon = floatingLogo.querySelector('.logo-icon');
        const logoText = floatingLogo.querySelector('.logo-text');

        let LARGE_ICON_SIZE = 56;
        let SMALL_ICON_SIZE = 36;
        let LARGE_TEXT_SIZE_REM = 2.2;
        let SMALL_TEXT_SIZE_REM = 1.3;

        let bannerCenterY = 0;
        let headerCenterY = 0;

        function updatePositions() {
            const bannerRect = banner.getBoundingClientRect();
            bannerCenterY = bannerRect.top + bannerRect.height / 2;

            const width = window.innerWidth;
            const isMobile = width <= 768;
            LARGE_ICON_SIZE = isMobile ? 32 : 56;
            SMALL_ICON_SIZE = isMobile ? 24 : 36;
            LARGE_TEXT_SIZE_REM = isMobile ? 1.0 : 2.2;
            SMALL_TEXT_SIZE_REM = isMobile ? 0.8 : 1.3;

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
                sliderTrack.style.transform = `translateX(${currentIndex * 100}%)`;

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

            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    stopAutoSlide();
                } else {
                    startAutoSlide();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowLeft') { nextSlide(); resetAutoSlide(); }
                else if (e.key === 'ArrowRight') { prevSlide(); resetAutoSlide(); }
            });

            startAutoSlide();
        }
    }

    // 4. Glassmorphic Light AJAX Live Search & Keyboard Navigation
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('ajaxSearchInput');
    const searchSubmitBtn = document.getElementById('searchSubmitBtn');
    const resultsWrapper = document.getElementById('searchResultsWrapper');

    if (searchInput && resultsWrapper) {
        let debounceTimer;
        let selectedIndex = -1;

        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                const query = searchInput.value.trim();
                if (!query) {
                    e.preventDefault();
                    const inputWrapper = searchInput.closest('.search-input-wrapper');
                    if (inputWrapper) {
                        inputWrapper.classList.remove('shakeInput');
                        void inputWrapper.offsetWidth;
                        inputWrapper.classList.add('shakeInput');
                        setTimeout(() => inputWrapper.classList.remove('shakeInput'), 500);
                    }
                }
            });
        }

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
                        selectedIndex = -1;
                    } else {
                        resultsWrapper.classList.remove('active');
                    }
                })
                .catch(error => console.error('Error fetching search results:', error));
            }, 250);
        });

        // Keyboard Navigation (ArrowUp, ArrowDown, Enter, Esc)
        searchInput.addEventListener('keydown', function(e) {
            const items = resultsWrapper.querySelectorAll('.search-result-item');
            if (!items.length) return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                updateSelected(items);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, 0);
                updateSelected(items);
            } else if (e.key === 'Enter' && selectedIndex >= 0) {
                e.preventDefault();
                const selectedItem = items[selectedIndex];
                const link = selectedItem.getAttribute('href');
                if (link) window.location.href = link;
            } else if (e.key === 'Escape') {
                resultsWrapper.classList.remove('active');
            }
        });

        function updateSelected(items) {
            items.forEach((item, idx) => {
                if (idx === selectedIndex) {
                    item.classList.add('selected');
                    item.style.background = 'rgba(26, 95, 180, 0.12)';
                } else {
                    item.classList.remove('selected');
                    item.style.background = 'transparent';
                }
            });
        }

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !resultsWrapper.contains(e.target)) {
                resultsWrapper.classList.remove('active');
            }
        });
    }

    // 5. Gallery Lazy Load Skeleton & Bulletproof Lightbox Modal Logic
    const galleryGrid = document.getElementById('galleryGrid');
    const galleryImages = document.querySelectorAll('.gallery-item img');

    galleryImages.forEach(img => {
        const item = img.closest('.gallery-item');
        if (img.complete) {
            img.style.opacity = '1';
            if (item) item.classList.add('loaded');
        } else {
            img.addEventListener('load', () => {
                img.style.opacity = '1';
                if (item) item.classList.add('loaded');
            });
        }
        img.addEventListener('error', () => {
            if (item) item.classList.add('loaded');
        });
    });

    const lightbox = document.getElementById('lightbox');
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxClose = document.getElementById('lightboxClose');
    const lightboxPrev = document.getElementById('lightboxPrev');
    const lightboxNext = document.getElementById('lightboxNext');
    const lightboxCounter = document.getElementById('lightboxCounter');

    if (galleryGrid && lightbox && lightboxImage) {
        const items = document.querySelectorAll('.gallery-item');
        const galleryItemsList = Array.from(items);
        let currentIndex = 0;
        let isTransitioning = false;
        let pendingIndex = null;
        let safetyTimer = null;

        function updateCounter(index) {
            if (lightboxCounter) {
                lightboxCounter.textContent = `${index + 1} / ${galleryItemsList.length}`;
            }
        }

        function releaseLock() {
            isTransitioning = false;
            if (safetyTimer) {
                clearTimeout(safetyTimer);
                safetyTimer = null;
            }
            if (pendingIndex !== null) {
                const nextIdx = pendingIndex;
                pendingIndex = null;
                changeImage(nextIdx);
            }
        }

        function openLightbox(index) {
            if (galleryItemsList.length === 0) return;
            const img = galleryItemsList[index].querySelector('img');
            if (!img) return;

            lightboxImage.src = img.src;
            lightboxImage.alt = img.alt || '';
            currentIndex = index;
            updateCounter(index);
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            lightbox.classList.remove('active');
            document.body.style.overflow = '';
        }

        function changeImage(newIndex) {
            if (galleryItemsList.length === 0) return;
            if (newIndex === currentIndex) return;

            if (isTransitioning) {
                pendingIndex = newIndex;
                return;
            }

            isTransitioning = true;
            pendingIndex = null;

            lightboxImage.style.opacity = '0';

            const targetImg = galleryItemsList[newIndex].querySelector('img');
            if (!targetImg) {
                releaseLock();
                return;
            }

            const newSrc = targetImg.src;
            const newAlt = targetImg.alt || '';

            const applyChange = () => {
                lightboxImage.src = newSrc;
                lightboxImage.alt = newAlt;
                currentIndex = newIndex;
                updateCounter(newIndex);
                lightboxImage.style.opacity = '1';
                releaseLock();
            };

            const preloader = new Image();
            let applied = false;

            const doApply = () => {
                if (!applied) {
                    applied = true;
                    applyChange();
                }
            };

            preloader.onload = doApply;
            preloader.onerror = doApply;

            safetyTimer = setTimeout(() => {
                if (!applied) {
                    doApply();
                }
            }, 3000);

            preloader.src = newSrc;

            if (preloader.complete) {
                doApply();
            }
        }

        function showPrev() {
            const newIndex = (currentIndex - 1 + galleryItemsList.length) % galleryItemsList.length;
            changeImage(newIndex);
        }

        function showNext() {
            const newIndex = (currentIndex + 1) % galleryItemsList.length;
            changeImage(newIndex);
        }

        galleryGrid.addEventListener('click', function(e) {
            const item = e.target.closest('.gallery-item');
            if (!item) return;
            const index = galleryItemsList.indexOf(item);
            if (index !== -1) openLightbox(index);
        });

        if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);
        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) closeLightbox();
        });

        if (lightboxPrev) lightboxPrev.addEventListener('click', showPrev);
        if (lightboxNext) lightboxNext.addEventListener('click', showNext);

        document.addEventListener('keydown', function(e) {
            if (!lightbox.classList.contains('active')) return;
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') showPrev();
            if (e.key === 'ArrowRight') showNext();
        });

        // Touch Swipe Handling
        let touchStartX = 0;
        lightbox.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        lightbox.addEventListener('touchend', function(e) {
            const touchEndX = e.changedTouches[0].screenX;
            const diff = touchStartX - touchEndX;
            if (Math.abs(diff) < 50) return;
            if (diff > 0) showNext();
            else showPrev();
        }, { passive: true });
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
