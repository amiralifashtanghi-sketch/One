<div class="container" style="padding: 2rem 0;">
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1 style="font-size: 2.25rem; color: var(--color-text);">نمونه‌کارها و اثبات توانایی EAFD</h1>
        <p style="color: var(--color-text-muted); max-width: 650px; margin: 0.5rem auto 0;">ارزیابی پروژه‌های اجراشده بر پایه متریک‌های ملموس، معماری کلاینت و نتایج تست لایت‌هاوس</p>
    </div>

    <div class="grid grid-cols-3">
        <?php foreach ($items as $item): ?>
            <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="font-size: 0.8rem; font-weight: bold; color: var(--color-primary); margin-bottom: 0.5rem; text-transform: uppercase;"><?= \App\Core\Security::escape($item['service_type']) ?></div>
                    <h2 style="font-size: 1.25rem; color: var(--color-text); margin-bottom: 0.75rem;"><?= \App\Core\Security::escape($item['title']) ?></h2>
                    <p style="font-size: 0.875rem; color: var(--color-text-muted); line-height: 1.7; margin-bottom: 1.25rem;"><?= \App\Core\Security::escape($item['summary']) ?></p>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: var(--color-accent); font-family: monospace; background: rgba(255, 138, 0, 0.1); padding: 0.4rem 0.75rem; border-radius: var(--radius-sm); margin-bottom: 1rem;">
                        تکنولوژی: <?= \App\Core\Security::escape($item['technologies']) ?>
                    </div>
                    <a href="/portfolio/<?= \App\Core\Security::escape($item['slug']) ?>" class="btn btn-outline" style="width: 100%; text-align: center; font-size: 0.85rem;">مطالعه کامل Case Study ←</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
