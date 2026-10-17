<?php
ob_start();
?>

<h2 style="margin-top: 0; margin-bottom: 1.5rem;">تاریخچه کارت‌های فروخته‌شده و اصلاح شماره</h2>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>کد کارت</th>
                <th>شماره همراه خریدار</th>
                <th>آدرس بیو خریدار</th>
                <th>تاریخ و ساعت (شمسی)</th>
                <th>عملیات اصلاح شماره</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($assignedCards)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--muted);">هیچ کارتی تاکنون توسط شما فروخته نشده است.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($assignedCards as $item): ?>
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
                        <td><?= htmlspecialchars(\App\Core\Helper::toJalali($item['assigned_at'])) ?></td>
                        <td>
                            <form method="POST" action="/seller/correct-phone" style="display: flex; gap: 0.25rem;">
                                <input type="hidden" name="card_id" value="<?= $item['id'] ?>">
                                <input type="text" name="correct_phone" placeholder="اصلاح شماره همراه" style="width: 130px; padding: 0.35rem; font-size: 0.8rem;" required>
                                <button type="submit" class="btn" style="padding: 0.35rem 0.6rem; font-size: 0.8rem;">اصلاح ✏️</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
