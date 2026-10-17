<?php
ob_start();
?>

<h2 style="margin-top: 0; margin-bottom: 1.5rem;">مدیریت فروش و گزارش مالی</h2>

<div class="stats-grid">
    <div class="stat-card">
        <span class="stat-title">تعداد کل کارت‌های فروخته شده</span>
        <span class="stat-value" style="color: var(--primary);"><?= number_format($totalSales) ?> کارت</span>
    </div>
    <div class="stat-card">
        <span class="stat-title">فروش امروز</span>
        <span class="stat-value" style="color: var(--success);"><?= number_format($todaySales) ?> کارت</span>
    </div>
    <div class="stat-card">
        <span class="stat-title">فروش ماه جاری</span>
        <span class="stat-value" style="color: var(--warning);"><?= number_format($monthSales) ?> کارت</span>
    </div>
</div>

<div class="card">
    <h3 style="margin-top:0; color: var(--primary);">تاریخچه کامل تراکنش‌ها و فعال‌سازی کارت‌ها</h3>
    <table>
        <thead>
            <tr>
                <th>کد کارت</th>
                <th>فروشنده مربوطه</th>
                <th>شماره خریدار</th>
                <th>بیو خریدار</th>
                <th>توضیحات</th>
                <th>تاریخ و ساعت دقیق (شمسی)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($historyList)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--muted);">هنوز تراکنشی ثبت نشده است.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($historyList as $item): ?>
                    <tr>
                        <td><code><?= htmlspecialchars($item['card_code']) ?></code></td>
                        <td>
                            <strong><?= htmlspecialchars($item['seller_name'] ?? 'مدیر کل / سیستم') ?></strong>
                            <?php if (!empty($item['seller_phone'])): ?>
                                <br><small style="color: var(--muted);"><?= htmlspecialchars($item['seller_phone']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars($item['assigned_to_phone']) ?></strong></td>
                        <td>
                            <?php if (!empty($item['bio_username'])): ?>
                                <a href="/<?= htmlspecialchars($item['bio_username']) ?>" target="_blank" style="color: var(--primary); text-decoration: none;">/<?= htmlspecialchars($item['bio_username']) ?> 🔗</a>
                            <?php else: ?>
                                <span style="color: var(--muted);">-</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($item['notes'] ?? '-') ?></td>
                        <td><?= htmlspecialchars(\App\Core\Helper::toJalali($item['created_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
