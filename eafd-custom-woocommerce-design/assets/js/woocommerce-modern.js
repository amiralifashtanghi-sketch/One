document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('eafdMobileToggleBtn');
    const drawer = document.getElementById('eafdMobileDrawer');
    const overlay = document.getElementById('eafdMobileOverlay');

    if (toggleBtn && drawer && overlay) {
        toggleBtn.addEventListener('click', function () {
            drawer.classList.toggle('open');
            overlay.classList.toggle('active');
        });

        overlay.addEventListener('click', function () {
            drawer.classList.remove('open');
            overlay.classList.remove('active');
        });
    }
});
