/**
 * Floating Ajax Cart Drawer Script
 * eafd-theme
 */

document.addEventListener('DOMContentLoaded', function () {
	const cartTrigger = document.getElementById('eafd-floating-cart-btn');
	const cartDrawer = document.getElementById('eafd-cart-drawer');
	const drawerOverlay = document.getElementById('eafd-cart-drawer-overlay');
	const drawerClose = document.getElementById('eafd-cart-drawer-close');

	if (cartTrigger && cartDrawer && drawerOverlay) {
		function openDrawer() {
			cartDrawer.classList.add('eafd-drawer-open');
			drawerOverlay.classList.add('eafd-overlay-active');
			document.body.style.overflow = 'hidden';
		}

		function closeDrawer() {
			cartDrawer.classList.remove('eafd-drawer-open');
			drawerOverlay.classList.remove('eafd-overlay-active');
			document.body.style.overflow = '';
		}

		cartTrigger.addEventListener('click', function (e) {
			e.preventDefault();
			openDrawer();
		});

		if (drawerClose) {
			drawerClose.addEventListener('click', closeDrawer);
		}

		drawerOverlay.addEventListener('click', closeDrawer);

		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && cartDrawer.classList.contains('eafd-drawer-open')) {
				closeDrawer();
			}
		});
	}
});
