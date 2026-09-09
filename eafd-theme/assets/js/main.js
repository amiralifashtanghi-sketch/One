/**
 * Main Interactive JS for eafd-theme
 * Includes Slider logic with touch swipe, Navigation drawer, Quantity controls, and Cart drawer.
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
	// 2. HAMBURGER NAVIGATION DRAWER LOGIC
	// ==========================================
	const menuToggle = document.getElementById('eafdMenuToggle');
	const navDrawer = document.getElementById('eafdNavDrawer');
	const navOverlay = document.getElementById('eafdNavOverlay');
	const navClose = document.getElementById('eafdNavClose');

	function openNav() {
		if (navDrawer && navOverlay) {
			navDrawer.classList.add('eafd-drawer-open');
			navOverlay.classList.add('eafd-overlay-active');
		}
	}

	function closeNav() {
		if (navDrawer && navOverlay) {
			navDrawer.classList.remove('eafd-drawer-open');
			navOverlay.classList.remove('eafd-overlay-active');
		}
	}

	if (menuToggle) menuToggle.addEventListener('click', openNav);
	if (navClose) navClose.addEventListener('click', closeNav);
	if (navOverlay) navOverlay.addEventListener('click', closeNav);

	// ==========================================
	// 3. FLOATING CART DRAWER LOGIC
	// ==========================================
	const cartBtn = document.getElementById('eafdFloatingCartBtn');
	const cartDrawer = document.getElementById('eafdCartDrawer');
	const cartOverlay = document.getElementById('eafdCartOverlay');
	const cartClose = document.getElementById('eafdCartClose');

	function openCart() {
		if (cartDrawer && cartOverlay) {
			cartDrawer.classList.add('eafd-drawer-open');
			cartOverlay.classList.add('eafd-overlay-active');
		}
	}

	function closeCart() {
		if (cartDrawer && cartOverlay) {
			cartDrawer.classList.remove('eafd-drawer-open');
			cartOverlay.classList.remove('eafd-overlay-active');
		}
	}

	if (cartBtn) cartBtn.addEventListener('click', openCart);
	if (cartClose) cartClose.addEventListener('click', closeCart);
	if (cartOverlay) cartOverlay.addEventListener('click', closeCart);

	// ==========================================
	// 4. QUANTITY CONTROLS (+ / -) LOGIC
	// ==========================================
	document.body.addEventListener('click', function (e) {
		if (e.target.classList.contains('eafd-qty-plus')) {
			const input = e.target.parentElement.querySelector('input.qty');
			if (input) {
				const val = parseInt(input.value) || 0;
				const max = parseInt(input.getAttribute('max')) || 999;
				if (val < max) {
					input.value = val + 1;
					input.dispatchEvent(new Event('change', { bubbles: true }));
				}
			}
		}

		if (e.target.classList.contains('eafd-qty-minus')) {
			const input = e.target.parentElement.querySelector('input.qty');
			if (input) {
				const val = parseInt(input.value) || 0;
				const min = parseInt(input.getAttribute('min')) || 1;
				if (val > min) {
					input.value = val - 1;
					input.dispatchEvent(new Event('change', { bubbles: true }));
				}
			}
		}
	});
});
