<h2>گام ۲: بررسی سطح دسترسی فایل‌ها</h2>
<p>بررسی مجوزهای نوشتن فایل بر روی پوشه‌های سیستم:</p>

<table class="install-table">
    <thead>
        <tr>
            <th>مسیر پوشه</th>
            <th>وضعیت دسترسی</th>
        </tr>
    </thead>
    <tbody>
        <?php $allPass = true; foreach ($permissions as $item): if (!$item['pass']) $allPass = false; ?>
        <tr>
            <td><code><?= htmlspecialchars($item['name']) ?></code></td>
            <td>
                <?php if ($item['pass']): ?>
                    <span class="badge badge-success">✓ قابل نوشتن</span>
                <?php else: ?>
                    <span class="badge badge-danger">✕ غیرقابل نوشتن (775/777)</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if ($allPass): ?>
    <a href="index.php?step=3" class="btn btn-primary">ادامه به گام بعدی (تنظیمات دیتابیس) ←</a>
<?php else: ?>
    <p class="error-msg">لطفاً مجوز نوشتن (CHMOD 775 یا 777) را به پوشه‌های فوق اعطا کنید.</p>
<?php endif; ?>
