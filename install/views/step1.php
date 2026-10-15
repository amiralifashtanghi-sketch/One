<h2>گام ۱: بررسی پیش‌نیازهای سرور</h2>
<p>سیستم در حال بررسی سازگاری هاست اشتراکی لینوکس شما می‌باشد:</p>

<table class="install-table">
    <thead>
        <tr>
            <th>عنوان پیش‌نیاز</th>
            <th>مقدار فعلی</th>
            <th>وضعیت</th>
        </tr>
    </thead>
    <tbody>
        <?php $allPass = true; foreach ($checks as $item): if (!$item['pass']) $allPass = false; ?>
        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= htmlspecialchars($item['value']) ?></td>
            <td>
                <?php if ($item['pass']): ?>
                    <span class="badge badge-success">✓ تایید</span>
                <?php else: ?>
                    <span class="badge badge-danger">✕ خطا</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if ($allPass): ?>
    <a href="index.php?step=2" class="btn btn-primary">ادامه به گام بعدی (سطح دسترسی‌ها) ←</a>
<?php else: ?>
    <p class="error-msg">لطفاً ابتدا پیش‌نیازهای فوق را در سرور برطرف کرده و مجدداً تلاش کنید.</p>
<?php endif; ?>
