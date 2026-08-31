<?php $baseUrl = \App\Core\Security::getBaseUrl(); ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 style="font-size: 1.5rem; color: var(--color-text);">مدیریت خدمات اصلی EAFD</h1>
    <a href="<?= $baseUrl ?>/admin/services/create" class="btn btn-primary">+ افزودن خدمت جدید</a>
</div>

<div class="card">
    <?php if (empty($services)): ?>
        <p style="text-align: center; color: var(--color-text-muted); padding: 2rem 0;">هیچ خدمتی تاکنون تعریف نشده است.</p>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse; text-align: right; font-size: 0.9rem;">
            <thead>
                <tr style="border-bottom: 1px solid var(--color-surface-border); color: var(--color-text-muted);">
                    <th style="padding: 0.75rem;">ترتیب</th>
                    <th style="padding: 0.75rem;">عنوان خدمت</th>
                    <th style="padding: 0.75rem;">نام مستعار (Slug)</th>
                    <th style="padding: 0.75rem;">وضعیت</th>
                    <th style="padding: 0.75rem;">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $s): ?>
                    <tr style="border-bottom: 1px solid var(--color-surface-border);">
                        <td style="padding: 1rem 0.75rem; font-weight: bold; color: var(--color-primary);"><?= $s['sort_order'] ?></td>
                        <td style="padding: 1rem 0.75rem; font-weight: 600; color: var(--color-text);"><?= \App\Core\Security::escape($s['title']) ?></td>
                        <td style="padding: 1rem 0.75rem; color: var(--color-text-muted); font-family: monospace;"><?= \App\Core\Security::escape($s['slug']) ?></td>
                        <td style="padding: 1rem 0.75rem;">
                            <span style="font-size: 0.75rem; padding: 0.2rem 0.5rem; border-radius: var(--radius-sm); background: <?= $s['is_active'] ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)' ?>; color: <?= $s['is_active'] ? 'var(--color-success)' : 'var(--color-danger)' ?>;">
                                <?= $s['is_active'] ? 'فعال' : 'غیرفعال' ?>
                            </span>
                        </td>
                        <td style="padding: 1rem 0.75rem; display: flex; gap: 0.5rem;">
                            <a href="<?= $baseUrl ?>/admin/services/edit/<?= $s['id'] ?>" class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">ویرایش</a>
                            <a href="<?= $baseUrl ?>/admin/services/delete/<?= $s['id'] ?>" onclick="return confirm('آیا از حذف این خدمت اطمینان دارید؟');" class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.8rem; border-color: var(--color-danger); color: var(--color-danger);">حذف</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
