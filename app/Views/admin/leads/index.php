<?php $baseUrl = \App\Core\Security::getBaseUrl(); ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 style="font-size: 1.5rem; color: var(--color-text);">مدیریت درخواست‌های پروژه (Leads)</h1>
</div>

<div class="card">
    <?php if (empty($leads)): ?>
        <p style="text-align: center; color: var(--color-text-muted); padding: 2rem 0;">هیچ درخواستی تاکنون دریافت نشده است.</p>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse; text-align: right; font-size: 0.9rem;">
            <thead>
                <tr style="border-bottom: 1px solid var(--color-surface-border); color: var(--color-text-muted);">
                    <th style="padding: 0.75rem;">نام و نام خانوادگی</th>
                    <th style="padding: 0.75rem;">شماره تماس</th>
                    <th style="padding: 0.75rem;">نوع پروژه</th>
                    <th style="padding: 0.75rem;">بودجه</th>
                    <th style="padding: 0.75rem;">وضعیت</th>
                    <th style="padding: 0.75rem;">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leads as $l): ?>
                    <tr style="border-bottom: 1px solid var(--color-surface-border);">
                        <td style="padding: 1rem 0.75rem; font-weight: 600; color: var(--color-text);"><?= \App\Core\Security::escape($l['name']) ?></td>
                        <td style="padding: 1rem 0.75rem; color: var(--color-primary); font-family: monospace;"><?= \App\Core\Security::escape($l['phone']) ?></td>
                        <td style="padding: 1rem 0.75rem; color: var(--color-text);"><?= \App\Core\Security::escape($l['project_type']) ?></td>
                        <td style="padding: 1rem 0.75rem; color: var(--color-accent);"><?= \App\Core\Security::escape($l['budget'] ?? 'نامشخص') ?></td>
                        <td style="padding: 1rem 0.75rem;">
                            <span style="font-size: 0.75rem; padding: 0.2rem 0.5rem; border-radius: var(--radius-sm); background: rgba(0, 240, 255, 0.1); color: var(--color-primary);">
                                <?= \App\Core\Security::escape($l['status']) ?>
                            </span>
                        </td>
                        <td style="padding: 1rem 0.75rem; display: flex; gap: 0.5rem;">
                            <a href="<?= $baseUrl ?>/admin/leads/show/<?= $l['id'] ?>" class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">بررسی و پاسخ</a>
                            <a href="<?= $baseUrl ?>/admin/leads/delete/<?= $l['id'] ?>" onclick="return confirm('آیا از حذف این درخواست اطمینان دارید؟');" class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.8rem; border-color: var(--color-danger); color: var(--color-danger);">حذف</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
