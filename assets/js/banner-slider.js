// Multi-Banner Slider JS Logic with Keyboard Nav & Hover Pause
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('sliderContainer');
        const track = document.getElementById('bannerSliderTrack');
        if (!container || !track) return;

        const slides = track.querySelectorAll('.slide');
        const dots = container.querySelectorAll('.dot');
        const prevBtn = document.getElementById('sliderPrev');
        const nextBtn = document.getElementById('sliderNext');

        const totalSlides = slides.length;
        if (totalSlides <= 1) return;

        let currentIndex = 0;
        let autoSlideInterval = null;
        const autoScroll = container.getAttribute('data-autoscroll') === '1';
        const speed = parseInt(container.getAttribute('data-speed'), 10) || 4000;

        function goToSlide(index) {
            if (index < 0) index = totalSlides - 1;
            if (index >= totalSlides) index = 0;

            currentIndex = index;
            track.style.transform = `translateX(-${currentIndex * 100}%)`;

            dots.forEach((dot, idx) => {
                dot.classList.toggle('active', idx === currentIndex);
            });
        }

        function nextSlide() {
            goToSlide(currentIndex + 1);
        }

        function prevSlide() {
            goToSlide(currentIndex - 1);
        }

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

        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                nextSlide();
                resetAutoSlide();
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                prevSlide();
                resetAutoSlide();
            });
        }

        dots.forEach((dot, idx) => {
            dot.addEventListener('click', function() {
                goToSlide(idx);
                resetAutoSlide();
            });
        });

        container.addEventListener('mouseenter', stopAutoSlide);
        container.addEventListener('mouseleave', startAutoSlide);

        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                nextSlide();
                resetAutoSlide();
            } else if (e.key === 'ArrowRight') {
                prevSlide();
                resetAutoSlide();
            }
        });

        startAutoSlide();
    });
})();
