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
	// 4. QUANTITY CONTROLS (+ / -) & AJAX CART LOGIC
	// ==========================================
	let cartUpdateTimeout = null;

	document.body.addEventListener('click', function (e) {
		if (e.target.classList.contains('eafd-qty-plus')) {
			const qtyBox = e.target.closest('.eafd-cart-quantity-box, .eafd-quantity-control');
			const input = qtyBox ? qtyBox.querySelector('input.qty') : null;
			if (input) {
				const val = parseInt(input.value) || 0;
				const max = parseInt(input.getAttribute('max')) || 999;
				if (val < max) {
					input.value = val + 1;
					triggerCartUpdate(qtyBox);
				}
			}
		}

		if (e.target.classList.contains('eafd-qty-minus')) {
			const qtyBox = e.target.closest('.eafd-cart-quantity-box, .eafd-quantity-control');
			const input = qtyBox ? qtyBox.querySelector('input.qty') : null;
			if (input) {
				const val = parseInt(input.value) || 0;
				const min = parseInt(input.getAttribute('min')) || 1;
				if (val > min) {
					input.value = val - 1;
					triggerCartUpdate(qtyBox);
				}
			}
		}

		// Accordion Toggles
		const accordionHeader = e.target.closest('.js-eafd-toggle-accordion, .eafd-accordion-header');
		if (accordionHeader) {
			const card = accordionHeader.closest('.eafd-accordion-card');
			if (card) {
				card.classList.toggle('is-open');
			}
		}

		// Apply Coupon Bridge Button
		if (e.target.classList.contains('js-eafd-apply-coupon-btn')) {
			const couponInput = document.getElementById('cart_totals_coupon_code');
			const mainCouponInput = document.getElementById('coupon_code');
			const mainApplyBtn = document.querySelector('button[name="apply_coupon"]');
			if (couponInput && mainCouponInput) {
				mainCouponInput.value = couponInput.value;
				if (mainApplyBtn) mainApplyBtn.click();
			}
		}

		// Place Order Button Loading State
		if (e.target.id === 'place_order' || e.target.closest('#place_order')) {
			const placeBtn = document.getElementById('place_order');
			if (placeBtn && !placeBtn.classList.contains('is-processing')) {
				setTimeout(function () {
					placeBtn.classList.add('is-processing');
					placeBtn.innerHTML = '⏳ در حال پردازش سفارش...';
				}, 100);
			}
		}
	});

	function triggerCartUpdate(qtyBox) {
		if (qtyBox) qtyBox.classList.add('is-loading');

		if (cartUpdateTimeout) clearTimeout(cartUpdateTimeout);

		cartUpdateTimeout = setTimeout(function () {
			const updateBtn = document.querySelector('button[name="update_cart"]');
			if (updateBtn) {
				updateBtn.disabled = false;
				updateBtn.click();
			}
		}, 400);
	}
});
