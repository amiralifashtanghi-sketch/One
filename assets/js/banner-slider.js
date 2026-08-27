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
