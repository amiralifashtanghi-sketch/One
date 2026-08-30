<div class="container" style="max-width: 850px; padding: 2rem 0;">
    <nav aria-label="مسیر صفحه" style="font-size: 0.85rem; margin-bottom: 1.5rem; color: var(--color-text-dim);">
        <a href="/" style="color: var(--color-text-muted);">صفحه اصلی</a> /
        <a href="/portfolio" style="color: var(--color-text-muted);">نمونه‌کارها</a> /
        <span style="color: var(--color-primary);"><?= \App\Core\Security::escape($item['title']) ?></span>
    </nav>

    <div style="margin-bottom: 2.5rem; border-bottom: 1px solid var(--color-surface-border); padding-bottom: 2rem;">
        <span style="font-size: 0.85rem; font-weight: bold; color: var(--color-accent); font-family: monospace; text-transform: uppercase;"><?= \App\Core\Security::escape($item['service_type']) ?></span>
        <h1 style="font-size: 2.25rem; color: var(--color-text); margin: 0.5rem 0 1rem;"><?= \App\Core\Security::escape($item['title']) ?></h1>
        <p style="font-size: 1.1rem; color: var(--color-text-muted); line-height: 1.8; margin: 0;"><?= \App\Core\Security::escape($item['summary']) ?></p>
    </div>

    <!-- Structured Technical Sections -->
    <div style="display: flex; flex-direction: column; gap: 2rem; margin-bottom: 3rem;">
        <?php if (!empty($item['challenge'])): ?>
            <div class="card" style="border-right: 4px solid var(--color-danger);">
                <h2 style="font-size: 1.2rem; color: var(--color-danger); margin-bottom: 0.75rem;">۱. چالش‌های فنی و اولیه پروژه (The Challenge)</h2>
                <div style="color: var(--color-text); line-height: 1.8; font-size: 0.95rem;"><?= nl2br(\App\Core\Security::escape($item['challenge'])) ?></div>
            </div>
        <?php endif; ?>

        <?php if (!empty($item['solution'])): ?>
            <div class="card" style="border-right: 4px solid var(--color-primary);">
                <h2 style="font-size: 1.2rem; color: var(--color-primary); margin-bottom: 0.75rem;">۲. راهکار و معماری پیاده‌سازی شده (EAFD Solution)</h2>
                <div style="color: var(--color-text); line-height: 1.8; font-size: 0.95rem;"><?= nl2br(\App\Core\Security::escape($item['solution'])) ?></div>
            </div>
        <?php endif; ?>

        <?php if (!empty($item['results'])): ?>
            <div class="card" style="border-right: 4px solid var(--color-success); background: rgba(16, 185, 129, 0.05);">
                <h2 style="font-size: 1.2rem; color: var(--color-success); margin-bottom: 0.75rem;">۳. نتایج ملموس و دستاوردهای پروژه (Measurable Results)</h2>
                <div style="color: var(--color-text); line-height: 1.8; font-size: 0.95rem; font-weight: 500;"><?= nl2br(\App\Core\Security::escape($item['results'])) ?></div>
            </div>
        <?php endif; ?>
    </div>
</div>
