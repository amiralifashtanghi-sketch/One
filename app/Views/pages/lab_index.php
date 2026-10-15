<div class="container" style="padding: 2rem 0;">
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1 style="font-size: 2.25rem; color: var(--color-text);">آزمایشگاه توسعه و فناوری EAFD (LAB)</h1>
        <p style="color: var(--color-text-muted); max-width: 650px; margin: 0.5rem auto 0;">پروژه‌های تحقیقاتی، کانسپت‌های فنی تجربی، تست‌های کارایی و دستاوردهای ایجنتیک</p>
    </div>

    <div class="grid grid-cols-3">
        <?php foreach ($labs as $lab): ?>
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <span style="font-size: 0.75rem; font-family: monospace; color: var(--color-primary); background: rgba(0, 240, 255, 0.1); padding: 0.2rem 0.6rem; border-radius: var(--radius-sm);"><?= \App\Core\Security::escape($lab['technology']) ?></span>
                    <span style="font-size: 0.75rem; font-weight: bold; color: var(--color-accent); uppercase;"><?= \App\Core\Security::escape($lab['status']) ?></span>
                </div>
                <h2 style="font-size: 1.2rem; color: var(--color-text); margin-bottom: 0.75rem;"><?= \App\Core\Security::escape($lab['title']) ?></h2>
                <p style="font-size: 0.875rem; color: var(--color-text-muted); line-height: 1.7; margin-bottom: 1.5rem;"><?= \App\Core\Security::escape($lab['description']) ?></p>

                <?php if (!empty($lab['demo_url'])): ?>
                    <a href="<?= \App\Core\Security::escape($lab['demo_url']) ?>" target="_blank" class="btn btn-outline" style="width: 100%; text-align: center; font-size: 0.8rem;">مشاهده دمو آنلاین ↗</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
