<?php
ob_start();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h2 style="margin: 0;">ویرایش کامل بیو: /<?= htmlspecialchars($bio['username']) ?></h2>
    <a href="/<?= htmlspecialchars($bio['username']) ?>" target="_blank" class="btn" style="background: var(--primary);">مشاهده آنلاین بیو 🔗</a>
</div>

<div class="card" style="margin-bottom: 1.5rem;">
    <h3 style="margin-top: 0;">اطلاعات کلی بیو</h3>
    <p style="color: var(--muted); font-size: 0.9rem; margin-bottom: 1rem;">
        مالک بیو: <strong><?= htmlspecialchars($bio['user_name'] ?? 'کاربر') ?></strong> (<?= htmlspecialchars($bio['phone']) ?>)
    </p>

    <form method="POST" action="/admin/bios/save">
        <input type="hidden" name="bio_id" value="<?= $bio['id'] ?>">
        <div class="form-group">
            <label>یوزرنیم / اسلاگ عمومی:</label>
            <input type="text" name="username" value="<?= htmlspecialchars($bio['username']) ?>" required>
        </div>
        <div class="form-group">
            <label>عنوان صفحه بیو:</label>
            <input type="text" name="title" value="<?= htmlspecialchars($bio['title'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>توضیحات کوتاه (بیوگرافی):</label>
            <textarea name="description" rows="3"><?= htmlspecialchars($bio['description'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-success">ذخیره مشخصات بیو توسط مدیر</button>
    </form>
</div>

<div class="card">
    <h3 style="margin-top: 0;">بلوک‌های فعال این بیو (<?= count($blocks) ?> بلوک)</h3>
    <?php if (empty($blocks)): ?>
        <p style="color: var(--muted);">هیچ بلوکی برای این بیو هنوز ثبت نشده است.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>عنوان / نوع بلوک</th>
                    <th>نوع</th>
                    <th>وضعیت</th>
                    <th>تعداد کلیک</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($blocks as $block): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($block['title'] ?: 'بدون عنوان') ?></strong></td>
                        <td><code><?= htmlspecialchars($block['type']) ?></code></td>
                        <td>
                            <?php if ($block['is_active']): ?>
                                <span class="badge badge-success">فعال</span>
                            <?php else: ?>
                                <span class="badge badge-danger">غیرفعال</span>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= number_format($block['clicks_count']) ?></strong></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
