<header class="eafd-header">
    <div class="container eafd-header-inner">
        <a href="/" class="eafd-logo" aria-label="صفحه اصلی EAFD">
            <span class="eafd-logo-text">EAFD</span>
        </a>

        <?php \App\Core\View::partial('partials/nav'); ?>

        <div class="eafd-header-actions">
            <a href="/projects" class="eafd-btn-primary eafd-btn-desktop">
                شروع یک پروژه
                <?php \App\Core\View::partial('components/icons/arrow'); ?>
            </a>

            <button id="eafd-menu-toggle" class="eafd-hamburger-btn" aria-label="منوی ناوبری اصلی" aria-expanded="false" aria-controls="eafd-mobile-drawer">
                <span class="eafd-hamburger-line"></span>
                <span class="eafd-hamburger-line"></span>
                <span class="eafd-hamburger-line"></span>
            </button>
        </div>
    </div>
</header>

<div id="eafd-mobile-drawer" class="eafd-mobile-drawer" aria-hidden="true">
    <div class="eafd-drawer-overlay" id="eafd-drawer-overlay"></div>
    <div class="eafd-drawer-content" role="dialog" aria-modal="true" aria-label="منوی موبایل">
        <div class="eafd-drawer-header">
            <span class="eafd-logo-text">EAFD</span>
            <button id="eafd-drawer-close" class="eafd-drawer-close-btn" aria-label="بستن منو">✕</button>
        </div>
        <nav class="eafd-drawer-nav">
            <a href="/" class="eafd-drawer-link">صفحه اصلی</a>
            <a href="/services" class="eafd-drawer-link">خدمات</a>
            <a href="/projects" class="eafd-drawer-link">پروژه‌ها</a>
            <a href="/store" class="eafd-drawer-link">محصولات دیجیتال</a>
            <a href="/lab" class="eafd-drawer-link">آزمایشگاه (LAB)</a>
            <a href="/account" class="eafd-drawer-link">حساب کاربری</a>
            <a href="/admin" class="eafd-drawer-link">مدیریت</a>
        </nav>
        <div style="margin-top: var(--eafd-spacing-xl);">
            <a href="/projects" class="eafd-btn-primary" style="width: 100%;">شروع پروژه</a>
        </div>
    </div>
</div>

<style>
.eafd-header {
    background: rgba(8, 8, 8, 0.85);
    border-bottom: 1px solid var(--eafd-color-border);
    padding: var(--eafd-spacing-md) 0;
    position: sticky;
    top: 0;
    z-index: 1000;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}

.eafd-header-inner {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.eafd-logo {
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

.eafd-logo-text {
    font-size: 1.4rem;
    font-weight: 800;
    letter-spacing: -0.03em;
    color: var(--eafd-color-text);
}

.eafd-header-actions {
    display: flex;
    align-items: center;
    gap: var(--eafd-spacing-md);
}

.eafd-hamburger-btn {
    display: none;
    flex-direction: column;
    justify-content: space-between;
    width: 32px;
    height: 24px;
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 0;
}

.eafd-hamburger-line {
    width: 100%;
    height: 3px;
    background-color: var(--eafd-color-text);
    border-radius: 2px;
    transition: all 0.2s ease;
}

@media (max-width: 768px) {
    .eafd-btn-desktop {
        display: none;
    }
    .eafd-hamburger-btn {
        display: flex;
    }
}

/* Drawer System */
.eafd-mobile-drawer {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 9999;
    visibility: hidden;
    opacity: 0;
    transition: visibility 0.2s ease, opacity 0.2s ease;
}

.eafd-mobile-drawer.is-open {
    visibility: visible;
    opacity: 1;
}

.eafd-drawer-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(4px);
}

.eafd-drawer-content {
    position: absolute;
    top: 0;
    right: 0;
    width: 280px;
    max-width: 85vw;
    height: 100%;
    background: #121826;
    border-left: 1px solid var(--eafd-color-border);
    padding: var(--eafd-spacing-lg);
    display: flex;
    flex-direction: column;
    box-shadow: -5px 0 25px rgba(0,0,0,0.5);
    transform: translateX(100%);
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}

.eafd-mobile-drawer.is-open .eafd-drawer-content {
    transform: translateX(0);
}

.eafd-drawer-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--eafd-spacing-xl);
}

.eafd-drawer-close-btn {
    background: transparent;
    border: none;
    color: var(--eafd-color-text);
    font-size: 1.5rem;
    cursor: pointer;
}

.eafd-drawer-nav {
    display: flex;
    flex-direction: column;
    gap: var(--eafd-spacing-md);
}

.eafd-drawer-link {
    color: var(--eafd-color-text-muted);
    font-size: 1.1rem;
    font-weight: 600;
    text-decoration: none;
    padding: 8px 0;
    border-bottom: 1px solid rgba(255,255,255,0.05);
}

.eafd-drawer-link:hover {
    color: var(--eafd-color-text);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('eafd-menu-toggle');
    const drawer = document.getElementById('eafd-mobile-drawer');
    const closeBtn = document.getElementById('eafd-drawer-close');
    const overlay = document.getElementById('eafd-drawer-overlay');

    if (toggleBtn && drawer) {
        function openDrawer() {
            drawer.classList.add('is-open');
            drawer.setAttribute('aria-hidden', 'false');
            toggleBtn.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
        }

        function closeDrawer() {
            drawer.classList.remove('is-open');
            drawer.setAttribute('aria-hidden', 'true');
            toggleBtn.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        }

        toggleBtn.addEventListener('click', openDrawer);
        if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
        if (overlay) overlay.addEventListener('click', closeDrawer);

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && drawer.classList.contains('is-open')) {
                closeDrawer();
            }
        });
    }
});
</script>
