<?php
ob_start();
?>

<h2 style="margin-top: 0; margin-bottom: 1.5rem;">ویرایش مشخصات پروفایل بیو</h2>

<div class="card" style="max-width: 600px;">
    <form method="POST" action="/dashboard/profile" enctype="multipart/form-data">
        <div class="form-group">
            <label>یوزرنیم / آدرس عمومی (slug):</label>
            <input type="text" name="username" value="<?= htmlspecialchars($bio['username']) ?>" required>
            <div style="font-size: 0.8rem; color: var(--muted); margin-top: 0.25rem;">
                آدرس صفحه بیو شما به صورت <code>domain.com/<?= htmlspecialchars($bio['username']) ?></code> خواهد بود.
            </div>
        </div>

        <div class="form-group">
            <label>عنوان اصلی (نام یا اسم برند شما):</label>
            <input type="text" name="title" value="<?= htmlspecialchars($bio['title'] ?? '') ?>" placeholder="مثال: علی محمدی | طراح وب">
        </div>

        <div class="form-group">
            <label>توضیحات کوتاه (بیوگرافی):</label>
            <textarea name="description" rows="3" placeholder="توضیح کوتاهی درباره خودتان یا کسب‌وکارتان..."><?= htmlspecialchars($bio['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>تصویر آواتار / لوگو:</label>
            <?php if (!empty($bio['avatar'])): ?>
                <div style="margin-bottom: 0.5rem;">
                    <img src="<?= htmlspecialchars($bio['avatar']) ?>" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary);">
                </div>
            <?php endif; ?>
            <input type="file" name="avatar" accept="image/*">
        </div>

        <button type="submit" class="btn btn-success" style="width: 100%;">ذخیره تغییرات مشخصات</button>
    </form>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
