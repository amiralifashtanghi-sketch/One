<div class="page-header" style="text-align:center; margin-bottom:40px;">
    <h1>کلیدهای لایسنس و فایل‌های دانلود محصول</h1>
</div>

<div class="card" style="max-width:900px; margin:0 auto;">
    <table class="install-table" style="width:100%;">
        <thead>
            <tr>
                <th>محصول</th>
                <th>کد لایسنس اختصاصی</th>
                <th>سقف دامنه</th>
                <th>وضعیت</th>
                <th>دانلود فایل</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($licenses)): ?>
                <tr><td colspan="5" style="text-align:center;">هیچ لایسنس فعالی برای حساب شما صادر نشده است.</td></tr>
            <?php else: ?>
                <?php foreach ($licenses as $lic): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($lic['product_title']) ?></strong> (v<?= htmlspecialchars($lic['product_version']) ?>)</td>
                        <td><code style="color:var(--eafd-color-secondary); font-weight:bold; font-size:1.05rem;"><?= htmlspecialchars($lic['license_key']) ?></code></td>
                        <td><?= $lic['max_domains'] ?> دامنه</td>
                        <td>
                            <?php if ($lic['status'] === 'active'): ?>
                                <span class="badge badge-success">فعال</span>
                            <?php else: ?>
                                <span class="badge badge-danger"><?= htmlspecialchars($lic['status']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($lic['status'] === 'active'): ?>
                                <a href="/download?product_id=<?= $lic['product_id'] ?>" class="btn btn-primary" style="padding:6px 14px; font-size:0.85rem;">دانلود فایل ZIP ⬇</a>
                            <?php else: ?>
                                <span style="color:var(--eafd-color-text-muted); font-size:0.85rem;">غیرقابل دانلود</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
