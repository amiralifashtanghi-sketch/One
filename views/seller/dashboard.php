<?php
ob_start();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h2 style="margin: 0;">خلاصه آمار عملکرد شما</h2>
    <a href="/seller/assign" class="btn">+ تحویل و تخصیص کارت جدید به خریدار</a>
</div>

<div class="card" style="display: inline-block; min-width: 280px; margin-bottom: 1.5rem;">
    <span style="font-size: 0.9rem; color: var(--muted);">تعداد کل کارت‌های فعال‌شده توسط شما:</span>
    <div style="font-size: 2.5rem; font-weight: bold; color: var(--primary); margin-top: 0.5rem;">
        <?= number_format($soldCount) ?> کارت
    </div>
</div>

<div class="card">
    <h3 style="margin-top:0;">آخرین کارت‌های فعال‌شده توسط شما</h3>
    <table>
        <thead>
            <tr>
                <th>کد کارت</th>
                <th>شماره همراه خریدار</th>
                <th>آدرس بیو خریدار</th>
                <th>تاریخ و زمان فعال‌سازی</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($recentAssigned)): ?>
                <tr>
                    <td colspan="4" style="text-align: center; color: var(--muted);">هنوز هیچ کارتی توسط شما فعال نشده است.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($recentAssigned as $item): ?>
                    <tr>
                        <td><code><?= htmlspecialchars($item['card_code']) ?></code></td>
                        <td><strong><?= htmlspecialchars($item['buyer_phone']) ?></strong></td>
                        <td>
                            <?php if ($item['bio_username']): ?>
                                <a href="/<?= htmlspecialchars($item['bio_username']) ?>" target="_blank" style="color: var(--primary); text-decoration: none;">
                                    /<?= htmlspecialchars($item['bio_username']) ?> 🔗
                                </a>
                            <?php else: ?>
                                <span style="color: var(--muted);">-</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($item['assigned_at'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
