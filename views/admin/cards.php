<?php
ob_start();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h2 style="margin: 0;">مدیریت کارت‌ها و QR کدها</h2>
    <form method="POST" action="/admin/cards/generate" style="display: flex; gap: 0.5rem; align-items: center;">
        <input type="number" name="count" value="10" min="1" max="100" style="width: 80px; text-align: center;">
        <button type="submit" class="btn btn-success">+ ساخت کارت جدید</button>
    </form>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>کد کارت</th>
                <th>لینک و کیو آر کد</th>
                <th>وضعیت</th>
                <th>مالک فعلی</th>
                <th>عملیات و تغییر مالک / رمز</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($cards)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--muted);">کارت یا کیو آر کدی یافت نشد.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($cards as $card): ?>
                    <?php
                        $cardUrl = $appUrl . '/' . $card['card_code'];
                        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($cardUrl);
                    ?>
                    <tr>
                        <td><code><?= htmlspecialchars($card['card_code']) ?></code></td>
                        <td>
                            <a href="<?= htmlspecialchars($cardUrl) ?>" target="_blank" style="color: var(--primary); text-decoration: none; font-size: 0.85rem;">مشاهده کارت 🔗</a>
                            <br>
                            <a href="<?= htmlspecialchars($qrUrl) ?>" target="_blank" style="color: var(--muted); text-decoration: none; font-size: 0.75rem;">دانلود QR Code 📷</a>
                        </td>
                        <td>
                            <?php if ($card['status'] === 'assigned'): ?>
                                <span class="badge badge-success">فروخته شده</span>
                            <?php elseif ($card['status'] === 'unassigned'): ?>
                                <span class="badge badge-warning">بدون مالک (آماده)</span>
                            <?php else: ?>
                                <span class="badge badge-danger">مسدود</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($card['user_phone']): ?>
                                <strong><?= htmlspecialchars($card['user_phone']) ?></strong>
                                <?php if ($card['bio_username']): ?>
                                    <br><small style="color: var(--muted);">@<?= htmlspecialchars($card['bio_username']) ?></small>
                                <?php endif; ?>
                            <?php else: ?>
                                <span style="color: var(--muted);">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <form method="POST" action="/admin/cards/reassign" style="display: flex; gap: 0.25rem; flex-direction: column;">
                                    <input type="hidden" name="card_id" value="<?= $card['id'] ?>">
                                    <input type="text" name="new_phone" placeholder="شماره همراه مالک" style="width: 150px; padding: 0.35rem; font-size: 0.8rem;" required>
                                    <input type="password" name="new_password" placeholder="رمز عبور مالک" style="width: 150px; padding: 0.35rem; font-size: 0.8rem;">
                                    <button type="submit" class="btn" style="padding: 0.35rem 0.6rem; font-size: 0.8rem;">ثبت/تغییر مالک و رمز</button>
                                </form>

                                <form method="POST" action="/admin/cards/update-status" style="display: inline; margin-top: auto;">
                                    <input type="hidden" name="card_id" value="<?= $card['id'] ?>">
                                    <input type="hidden" name="status" value="<?= $card['status'] === 'blocked' ? 'unassigned' : 'blocked' ?>">
                                    <button type="submit" class="btn <?= $card['status'] === 'blocked' ? 'btn-success' : 'btn-danger' ?>" style="padding: 0.35rem 0.6rem; font-size: 0.8rem;">
                                        <?= $card['status'] === 'blocked' ? 'رفع مسدودی' : 'مسدودسازی' ?>
                                    </button>
                                </form>
                            </div>
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
