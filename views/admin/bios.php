<?php
ob_start();
?>

<h2 style="margin-top: 0; margin-bottom: 1.5rem;">مدیریت صفحات بیو و محتوا</h2>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>یوزرنیم (آدرس بیو)</th>
                <th>مالک صفحه</th>
                <th>عنوان بیو</th>
                <th>تعداد بلوک‌ها</th>
                <th>بازدید کل</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($bios)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--muted);">هیچ صفحه بیویی هنوز ایجاد نشده است.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($bios as $b): ?>
                    <tr>
                        <td>
                            <a href="/<?= htmlspecialchars($b['username']) ?>" target="_blank" style="color: var(--primary); font-weight: bold; text-decoration: none;">
                                /<?= htmlspecialchars($b['username']) ?> 🔗
                            </a>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($b['user_name'] ?? 'کاربر') ?></strong>
                            <br><small style="color: var(--muted);"><?= htmlspecialchars($b['phone']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($b['title'] ?? 'بدون عنوان') ?></td>
                        <td><span class="badge badge-warning"><?= number_format($b['blocks_count']) ?> بلوک</span></td>
                        <td><strong><?= number_format($b['views_count']) ?></strong></td>
                        <td>
                            <a href="/admin/bios/edit/<?= $b['id'] ?>" class="btn" style="padding: 0.35rem 0.75rem; font-size: 0.85rem;">✏️ مدیریت محتوای بیو</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
