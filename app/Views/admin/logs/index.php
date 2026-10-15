<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
    <div>
        <h1 style="margin:0;">لاگ رخدادها و امنیت سامانه (Activity Logs)</h1>
        <p style="margin:5px 0 0 0; color:var(--eafd-color-text-muted);">ثبت خودکار ورودها، تغییرات لایسنس، سفارشات و عملیات مدیریتی</p>
    </div>
</div>

<div class="card">
    <table class="install-table" style="width:100%;">
        <thead>
            <tr>
                <th>شناسه کاربر</th>
                <th>عنوان اقدام / رخداد</th>
                <th>آدرس IP</th>
                <th>جزئیات Context</th>
                <th>زمان ثبت</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($logs)): ?>
                <tr><td colspan="5" style="text-align:center;">هیچ رخدادی ثبت نشده است.</td></tr>
            <?php else: ?>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><code>User #<?= $log['user_id'] ?></code></td>
                        <td><strong><?= htmlspecialchars($log['action']) ?></strong></td>
                        <td><code><?= htmlspecialchars($log['ip_address']) ?></code></td>
                        <td><small><?= htmlspecialchars($log['context']) ?></small></td>
                        <td><?= htmlspecialchars($log['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
