<?php $baseUrl = \App\Core\Security::getBaseUrl(); ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 style="font-size: 1.5rem; color: var(--color-text);">مدیریت نمونه‌کارها (مبتنی بر داده)</h1>
    <a href="<?= $baseUrl ?>/admin/portfolio/create" class="btn btn-primary">+ افزودن پروژه جدید</a>
</div>

<div class="card">
    <?php if (empty($items)): ?>
        <p style="text-align: center; color: var(--color-text-muted); padding: 2rem 0;">هیچ پروژه‌ای تاکنون ثبت نشده است.</p>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse; text-align: right; font-size: 0.9rem;">
            <thead>
                <tr style="border-bottom: 1px solid var(--color-surface-border); color: var(--color-text-muted);">
                    <th style="padding: 0.75rem;">عنوان پروژه</th>
                    <th style="padding: 0.75rem;">مشتری</th>
                    <th style="padding: 0.75rem;">نوع خدمت</th>
                    <th style="padding: 0.75rem;">تکنولوژی‌ها</th>
                    <th style="padding: 0.75rem;">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr style="border-bottom: 1px solid var(--color-surface-border);">
                        <td style="padding: 1rem 0.75rem; font-weight: 600; color: var(--color-text);"><?= \App\Core\Security::escape($item['title']) ?></td>
                        <td style="padding: 1rem 0.75rem; color: var(--color-text-muted);"><?= \App\Core\Security::escape($item['client_name'] ?? '-') ?></td>
                        <td style="padding: 1rem 0.75rem; color: var(--color-primary);"><?= \App\Core\Security::escape($item['service_type']) ?></td>
                        <td style="padding: 1rem 0.75rem; font-size: 0.8rem; color: var(--color-accent); font-family: monospace;"><?= \App\Core\Security::escape($item['technologies']) ?></td>
                        <td style="padding: 1rem 0.75rem; display: flex; gap: 0.5rem;">
                            <a href="<?= $baseUrl ?>/admin/portfolio/edit/<?= $item['id'] ?>" class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">ویرایش</a>
                            <a href="<?= $baseUrl ?>/admin/portfolio/delete/<?= $item['id'] ?>" onclick="return confirm('آیا از حذف این پروژه اطمینان دارید؟');" class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.8rem; border-color: var(--color-danger); color: var(--color-danger);">حذف</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
