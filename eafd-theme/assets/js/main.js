/**
 * Main Interactive JS for eafd-theme
 * Includes Slider logic with touch swipe and Accordion toggles.
 */

document.addEventListener('DOMContentLoaded', function () {
	// ==========================================
	// 1. HERO BANNER SLIDER LOGIC
	// ==========================================
	const slider = document.getElementById('eafdHeroSlider');
	if (slider) {
		const track = slider.querySelector('.eafd-slider-track');
		const slides = Array.from(slider.querySelectorAll('.eafd-slide'));
		const prevBtn = slider.querySelector('.eafd-slider-prev');
		const nextBtn = slider.querySelector('.eafd-slider-next');
		const dots = Array.from(slider.querySelectorAll('.eafd-slider-dot'));

		if (slides.length > 1) {
			let currentIndex = 0;
			let autoPlayTimer = null;
			let touchStartX = 0;
			let touchEndX = 0;

			function updateSlider(index) {
				if (index < 0) {
					currentIndex = slides.length - 1;
				} else if (index >= slides.length) {
					currentIndex = 0;
				} else {
					currentIndex = index;
				}

				track.style.transform = `translateX(${currentIndex * 100}%)`;

				slides.forEach((s, i) => s.classList.toggle('active', i === currentIndex));
				dots.forEach((d, i) => d.classList.toggle('active', i === currentIndex));
			}

			function startAutoPlay() {
				stopAutoPlay();
				autoPlayTimer = setInterval(function () {
					updateSlider(currentIndex + 1);
				}, 5000);
			}

			function stopAutoPlay() {
				if (autoPlayTimer) {
					clearInterval(autoPlayTimer);
				}
			}

			if (prevBtn) {
				prevBtn.addEventListener('click', function () {
					updateSlider(currentIndex - 1);
					startAutoPlay();
				});
			}

			if (nextBtn) {
				nextBtn.addEventListener('click', function () {
					updateSlider(currentIndex + 1);
					startAutoPlay();
				});
			}

			dots.forEach((dot, idx) => {
				dot.addEventListener('click', function () {
					updateSlider(idx);
					startAutoPlay();
				});
			});

			// Touch swipe gestures
			slider.addEventListener('touchstart', function (e) {
				touchStartX = e.changedTouches[0].screenX;
				stopAutoPlay();
			}, { passive: true });

			slider.addEventListener('touchend', function (e) {
				touchEndX = e.changedTouches[0].screenX;
				handleSwipe();
				startAutoPlay();
			}, { passive: true });

			function handleSwipe() {
				const swipeThreshold = 50;
				if (touchStartX - touchEndX > swipeThreshold) {
					// Swiped left
					updateSlider(currentIndex + 1);
				} else if (touchEndX - touchStartX > swipeThreshold) {
					// Swiped right
					updateSlider(currentIndex - 1);
				}
			}

			// Hover pause
			slider.addEventListener('mouseenter', stopAutoPlay);
			slider.addEventListener('mouseleave', startAutoPlay);

			// Init
			startAutoPlay();
		}
	}

	// ==========================================
	// 2. ACCORDION TOGGLES LOGIC
	// ==========================================
	document.body.addEventListener('click', function (e) {
		const accordionHeader = e.target.closest('.js-eafd-toggle-accordion, .eafd-accordion-header');
		if (accordionHeader) {
			const card = accordionHeader.closest('.eafd-accordion-card');
			if (card) {
				card.classList.toggle('is-open');
			}
		}
	});
});
