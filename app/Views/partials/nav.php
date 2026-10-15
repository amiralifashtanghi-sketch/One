<nav aria-label="ناوبری اصلی سایت" class="eafd-main-nav">
    <ul class="eafd-nav-list">
        <li><a href="/" class="eafd-nav-link">صفحه اصلی</a></li>
        <li><a href="/services" class="eafd-nav-link">خدمات</a></li>
        <li><a href="/projects" class="eafd-nav-link">پروژه‌ها</a></li>
        <li><a href="/store" class="eafd-nav-link">محصولات</a></li>
        <li><a href="/lab" class="eafd-nav-link">آزمایشگاه</a></li>
    </ul>
</nav>

<style>
.eafd-main-nav {
    display: flex;
    align-items: center;
}

.eafd-nav-list {
    display: flex;
    gap: var(--eafd-spacing-lg);
    list-style: none;
    margin: 0;
    padding: 0;
    align-items: center;
}

.eafd-nav-link {
    color: var(--eafd-color-text-muted);
    font-size: 0.95rem;
    font-weight: 500;
    transition: color var(--eafd-motion-speed) var(--eafd-motion-easing);
}

.eafd-nav-link:hover {
    color: var(--eafd-color-text);
}

@media (max-width: 768px) {
    .eafd-main-nav {
        display: none;
    }
}
</style>
