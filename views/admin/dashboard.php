<?php
ob_start();
?>

<h2 style="margin-top: 0; margin-bottom: 1.5rem;">داشبورد و خلاصه آمار سیستم</h2>

<div class="stats-grid">
    <div class="stat-card">
        <span class="stat-title">کل کارت‌های تولید شده</span>
        <span class="stat-value"><?= number_format($stats['total_cards']) ?></span>
    </div>
    <div class="stat-card">
        <span class="stat-title">کارت‌های فروخته شده / فعال</span>
        <span class="stat-value" style="color: var(--success);"><?= number_format($stats['assigned_cards']) ?></span>
    </div>
    <div class="stat-card">
        <span class="stat-title">کارت‌های بدون مالک (آماده)</span>
        <span class="stat-value" style="color: var(--warning);"><?= number_format($stats['unassigned_cards']) ?></span>
    </div>
    <div class="stat-card">
        <span class="stat-title">تعداد کل کاربران (مالکان بیو)</span>
        <span class="stat-value"><?= number_format($stats['total_users']) ?></span>
    </div>
    <div class="stat-card">
        <span class="stat-title">تعداد فروشندگان فعال</span>
        <span class="stat-value" style="color: var(--primary);"><?= number_format($stats['total_sellers']) ?></span>
    </div>
    <div class="stat-card">
        <span class="stat-title">مجموع بازدید صفحات بیو</span>
        <span class="stat-value"><?= number_format($stats['total_views']) ?></span>
    </div>
    <div class="stat-card">
        <span class="stat-title">درخواست‌های تماس (Leads)</span>
        <span class="stat-value"><?= number_format($stats['total_leads']) ?></span>
    </div>
    <div class="stat-card">
        <span class="stat-title">نوبت‌های ثبت‌شده (Bookings)</span>
        <span class="stat-value"><?= number_format($stats['total_bookings']) ?></span>
    </div>
</div>

<div class="card">
    <h3 style="margin-top:0;">آخرین کارت‌های ثبت شده در سیستم</h3>
    <table>
        <thead>
            <tr>
                <th>کد کارت</th>
                <th>وضعیت</th>
                <th>شماره خریدار (مالک)</th>
                <th>فروشنده مربوطه</th>
                <th>تاریخ تخصیص</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($recentCards)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--muted);">هیچ کارتی تاکنون ساخته نشده است.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($recentCards as $card): ?>
                    <tr>
                        <td><code><?= htmlspecialchars($card['card_code']) ?></code></td>
                        <td>
                            <?php if ($card['status'] === 'assigned'): ?>
                                <span class="badge badge-success">فروخته شده</span>
                            <?php elseif ($card['status'] === 'unassigned'): ?>
                                <span class="badge badge-warning">بدون مالک</span>
                            <?php else: ?>
                                <span class="badge badge-danger">مسدود</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($card['user_phone'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($card['seller_phone'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($card['assigned_at'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
