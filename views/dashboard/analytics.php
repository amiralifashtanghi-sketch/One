<?php
ob_start();
?>

<h2 style="margin-top: 0; margin-bottom: 1.5rem;">آمار بازدید و گزارش‌ها</h2>

<div class="card" style="margin-bottom: 1.5rem;">
    <h3 style="margin-top: 0;">نمودار بازدید ۷ روز اخیر</h3>
    <div style="display: flex; align-items: flex-end; gap: 1rem; height: 180px; padding-top: 2rem; border-bottom: 1px solid var(--border);">
        <?php foreach ($last7Days as $date => $cnt): ?>
            <?php
                $height = ($cnt > 0) ? min(150, max(20, $cnt * 10)) : 10;
            ?>
            <div style="flex: 1; display: flex; flex-direction: column; align-items: center;">
                <span style="font-size: 0.8rem; color: var(--primary); font-weight: bold; margin-bottom: 0.25rem;"><?= number_format($cnt) ?></span>
                <div style="width: 100%; max-width: 40px; height: <?= $height ?>px; background: var(--primary); border-radius: 6px 6px 0 0;"></div>
                <span style="font-size: 0.75rem; color: var(--muted); margin-top: 0.5rem;"><?= substr($date, 5) ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="card" style="margin-bottom: 1.5rem;">
    <h3 style="margin-top: 0;">درخواست‌های تماس دریافت شده (Leads)</h3>
    <table>
        <thead>
            <tr>
                <th>نام مخاطب</th>
                <th>شماره همراه</th>
                <th>ایمیل</th>
                <th>پیام</th>
                <th>تاریخ ثبت</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($leads)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--muted);">هیچ پیامی هنوز دریافت نشده است.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($leads as $lead): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($lead['name'] ?? '-') ?></strong></td>
                        <td><code><?= htmlspecialchars($lead['phone'] ?? '-') ?></code></td>
                        <td><?= htmlspecialchars($lead['email'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($lead['message'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($lead['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="card">
    <h3 style="margin-top: 0;">نوبت‌های رزرو شده (Bookings)</h3>
    <table>
        <thead>
            <tr>
                <th>نام رزرو کننده</th>
                <th>شماره همراه</th>
                <th>تاریخ رزرو</th>
                <th>ساعت رزرو</th>
                <th>توضیحات</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($bookings)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--muted);">هیچ نوبتی هنوز ثبت نشده است.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($bookings as $b): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($b['name']) ?></strong></td>
                        <td><code><?= htmlspecialchars($b['phone']) ?></code></td>
                        <td><?= htmlspecialchars($b['booking_date']) ?></td>
                        <td><?= htmlspecialchars($b['booking_time']) ?></td>
                        <td><?= htmlspecialchars($b['notes'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
