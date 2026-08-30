<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 1.5rem; color: var(--color-text);">پایش سلامت و امنیت زیرساخت EAFD</h1>
    <p style="font-size: 0.875rem; color: var(--color-text-muted); margin-top: 0.25rem;">ارزیابی خودکار منابع سرور، اتصال دیتابیس و وضعیت قفل‌های امنیتی</p>
</div>

<div class="card" style="max-width: 750px;">
    <ul style="list-style: none; display: flex; flex-direction: column; gap: 1rem;">
        <?php foreach ($healthChecks as $check): ?>
            <li style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md);">
                <div>
                    <div style="font-weight: 600; font-size: 0.95rem; color: var(--color-text);"><?= \App\Core\Security::escape($check['name']) ?></div>
                    <div style="font-size: 0.8rem; color: var(--color-text-muted); margin-top: 0.25rem;"><?= \App\Core\Security::escape($check['value']) ?></div>
                </div>
                <div>
                    <span style="font-size: 0.85rem; padding: 0.3rem 0.8rem; border-radius: var(--radius-sm); font-weight: bold; background: <?= $check['status'] ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)' ?>; color: <?= $check['status'] ? 'var(--color-success)' : 'var(--color-danger)' ?>;">
                        <?= $check['status'] ? 'تایید شد ✓' : 'هشدار ✗' ?>
                    </span>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
