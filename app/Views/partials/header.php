<header class="eafd-header">
    <div class="container eafd-header-inner">
        <a href="/" class="eafd-logo" aria-label="صفحه اصلی EAFD">
            <span class="eafd-logo-text">EAFD</span>
        </a>

        <?php \App\Core\View::partial('partials/nav'); ?>

        <div class="eafd-header-actions">
            <a href="/projects" class="eafd-btn-primary">
                شروع یک پروژه
                <?php \App\Core\View::partial('components/icons/arrow'); ?>
            </a>
        </div>
    </div>
</header>

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
</style>
