/**
 * Floating Ajax Cart Drawer & Mobile Nav Drawer Script
 * eafd-theme
 */

document.addEventListener('DOMContentLoaded', function () {
	// Floating Cart Drawer Logic
	const cartTrigger = document.getElementById('eafd-floating-cart-btn');
	const cartDrawer = document.getElementById('eafd-cart-drawer');
	const cartOverlay = document.getElementById('eafd-cart-drawer-overlay');
	const cartClose = document.getElementById('eafd-cart-drawer-close');

	if (cartTrigger && cartDrawer && cartOverlay) {
		function openCartDrawer() {
			cartDrawer.classList.add('eafd-drawer-open');
			cartOverlay.classList.add('eafd-overlay-active');
			document.body.style.overflow = 'hidden';
		}

		function closeCartDrawer() {
			cartDrawer.classList.remove('eafd-drawer-open');
			cartOverlay.classList.remove('eafd-overlay-active');
			document.body.style.overflow = '';
		}

		cartTrigger.addEventListener('click', function (e) {
			e.preventDefault();
			openCartDrawer();
		});

		if (cartClose) {
			cartClose.addEventListener('click', closeCartDrawer);
		}

		cartOverlay.addEventListener('click', closeCartDrawer);

		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && cartDrawer.classList.contains('eafd-drawer-open')) {
				closeCartDrawer();
			}
		});
	}

	// Mobile Nav Drawer Logic (Plan 4)
	const menuToggle = document.getElementById('eafd-menu-toggle');
	const navDrawer = document.getElementById('eafd-nav-drawer');
	const navOverlay = document.getElementById('eafd-nav-drawer-overlay');
	const navClose = document.getElementById('eafd-nav-drawer-close');

	if (menuToggle && navDrawer && navOverlay) {
		function openNavDrawer() {
			navDrawer.classList.add('eafd-drawer-open');
			navOverlay.classList.add('eafd-overlay-active');
			document.body.style.overflow = 'hidden';
		}

		function closeNavDrawer() {
			navDrawer.classList.remove('eafd-drawer-open');
			navOverlay.classList.remove('eafd-overlay-active');
			document.body.style.overflow = '';
		}

		menuToggle.addEventListener('click', function (e) {
			e.preventDefault();
			openNavDrawer();
		});

		if (navClose) {
			navClose.addEventListener('click', closeNavDrawer);
		}

		navOverlay.addEventListener('click', closeNavDrawer);

		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && navDrawer.classList.contains('eafd-drawer-open')) {
				closeNavDrawer();
			}
		});
	}

	// AJAX Category Filtering
	const catButtons = document.querySelectorAll('.eafd-cat-ajax-btn');
	const shopGrid = document.querySelector('.eafd-shop-grid');

	if (catButtons.length > 0 && shopGrid && typeof eafd_cart_params !== 'undefined') {
		catButtons.forEach(function (btn) {
			btn.addEventListener('click', function (e) {
				e.preventDefault();

				// Update active class
				catButtons.forEach(b => b.classList.remove('active'));
				btn.classList.add('active');

				const categorySlug = btn.getAttribute('data-category-slug') || '';

				// Add opacity loading state
				shopGrid.style.opacity = '0.4';
				shopGrid.style.pointerEvents = 'none';

				const formData = new FormData();
				formData.append('action', 'eafd_filter_products');
				formData.append('nonce', eafd_cart_params.nonce);
				formData.append('category_slug', categorySlug);

				fetch(eafd_cart_params.ajax_url, {
					method: 'POST',
					body: formData
				})
				.then(response => response.json())
				.then(data => {
					shopGrid.style.opacity = '1';
					shopGrid.style.pointerEvents = '';
					if (data.success && data.data && data.data.html) {
						shopGrid.innerHTML = data.data.html;

						// Scroll smoothly down to products section if needed
						const shopSection = document.querySelector('.eafd-shop-section');
						if (shopSection) {
							shopSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
						}
					}
				})
				.catch(err => {
					shopGrid.style.opacity = '1';
					shopGrid.style.pointerEvents = '';
					console.error('AJAX product filter error:', err);
				});
			});
		});
	}
});
