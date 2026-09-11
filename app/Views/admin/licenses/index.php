<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
    <div>
        <h1 style="margin:0;">مدیریت لایسنس‌های صادر شده</h1>
        <p style="margin:5px 0 0 0; color:var(--eafd-color-text-muted);">مشاهده، فعال‌سازی، ابطال و کنترل دامنه‌های فعال لایسنس‌ها</p>
    </div>
</div>

<div class="card">
    <table class="install-table" style="width:100%;">
        <thead>
            <tr>
                <th>کد لایسنس یکتا</th>
                <th>نام محصول</th>
                <th>خریدار</th>
                <th>سقف دامنه</th>
                <th>وضعیت</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($licenses)): ?>
                <tr><td colspan="6" style="text-align:center;">هیچ لایسنسی صادر نشده است.</td></tr>
            <?php else: ?>
                <?php foreach ($licenses as $lic): ?>
                    <tr>
                        <td><code style="color:var(--eafd-color-secondary); font-weight:bold;"><?= htmlspecialchars($lic['license_key']) ?></code></td>
                        <td><?= htmlspecialchars($lic['product_title']) ?></td>
                        <td><?= htmlspecialchars($lic['user_name']) ?> (<?= htmlspecialchars($lic['user_email']) ?>)</td>
                        <td><?= $lic['max_domains'] ?> دامنه</td>
                        <td>
                            <?php if ($lic['status'] === 'active'): ?>
                                <span class="badge badge-success">فعال</span>
                            <?php elseif ($lic['status'] === 'revoked'): ?>
                                <span class="badge badge-danger">باطل‌شده</span>
                            <?php else: ?>
                                <span class="badge badge-danger"><?= htmlspecialchars($lic['status']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="/admin/licenses/edit/<?= $lic['id'] ?>" class="btn btn-secondary" style="padding:4px 10px; font-size:0.85rem;">ویرایش</a>
                            <a href="/admin/licenses/revoke/<?= $lic['id'] ?>" class="btn btn-secondary" style="padding:4px 10px; font-size:0.85rem; color:#fca5a5;" onclick="return confirm('آیا از ابطال این لایسنس اطمینان دارید؟');">ابطال</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
