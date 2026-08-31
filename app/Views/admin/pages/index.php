<?php $baseUrl = \App\Core\Security::getBaseUrl(); ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 style="font-size: 1.5rem; color: var(--color-text);">مدیریت صفحات سیستم</h1>
    <a href="<?= $baseUrl ?>/admin/pages/create" class="btn btn-primary">+ ایجاد صفحه جدید</a>
</div>

<div class="card">
    <?php if (empty($pages)): ?>
        <p style="text-align: center; color: var(--color-text-muted); padding: 2rem 0;">هیچ صفحه‌ای تاکنون ثبت نشده است.</p>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse; text-align: right; font-size: 0.9rem;">
            <thead>
                <tr style="border-bottom: 1px solid var(--color-surface-border); color: var(--color-text-muted);">
                    <th style="padding: 0.75rem;">عنوان صفحه</th>
                    <th style="padding: 0.75rem;">مسیر (Slug)</th>
                    <th style="padding: 0.75rem;">وضعیت</th>
                    <th style="padding: 0.75rem;">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $p): ?>
                    <tr style="border-bottom: 1px solid var(--color-surface-border);">
                        <td style="padding: 1rem 0.75rem; font-weight: 600; color: var(--color-text);"><?= \App\Core\Security::escape($p['title']) ?></td>
                        <td style="padding: 1rem 0.75rem; color: var(--color-primary); font-family: monospace;"><?= \App\Core\Security::escape($p['slug']) ?></td>
                        <td style="padding: 1rem 0.75rem;">
                            <span style="font-size: 0.75rem; padding: 0.2rem 0.5rem; border-radius: var(--radius-sm); background: <?= $p['status'] === 'published' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(245, 158, 11, 0.1)' ?>; color: <?= $p['status'] === 'published' ? 'var(--color-success)' : 'var(--color-warning)' ?>;">
                                <?= $p['status'] === 'published' ? 'منتشر شده' : 'پیش‌نویس' ?>
                            </span>
                        </td>
                        <td style="padding: 1rem 0.75rem; display: flex; gap: 0.5rem;">
                            <a href="<?= $baseUrl ?>/admin/pages/edit/<?= $p['id'] ?>" class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">ویرایش</a>
                            <a href="<?= $baseUrl ?>/admin/pages/delete/<?= $p['id'] ?>" onclick="return confirm('آیا از حذف این صفحه اطمینان دارید؟');" class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.8rem; border-color: var(--color-danger); color: var(--color-danger);">حذف</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
