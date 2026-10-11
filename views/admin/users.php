<?php
ob_start();
?>

<h2 style="margin-top: 0; margin-bottom: 1.5rem;">مدیریت کاربران و فروشندگان</h2>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>نام و خانوادگی</th>
                <th>شماره همراه</th>
                <th>نقش کاربر</th>
                <th>تعداد فروش (کارت‌های فعال‌شده)</th>
                <th>لینک بیو</th>
                <th>وضعیت حساب</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--muted);">کاربری یافت نشد.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($user['name'] ?? '-') ?></strong></td>
                        <td><code><?= htmlspecialchars($user['phone']) ?></code></td>
                        <td>
                            <?php if ($user['role'] === 'admin'): ?>
                                <span class="badge badge-danger">مدیر کل</span>
                            <?php elseif ($user['role'] === 'seller'): ?>
                                <span class="badge badge-success">فروشنده</span>
                            <?php else: ?>
                                <span class="badge badge-warning">خریدار / کاربر عادی</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($user['role'] === 'seller'): ?>
                                <strong style="color: var(--success);"><?= number_format($user['sales_count']) ?> کارت</strong>
                            <?php else: ?>
                                <span style="color: var(--muted);">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($user['bio_username']): ?>
                                <a href="/<?= htmlspecialchars($user['bio_username']) ?>" target="_blank" style="color: var(--primary); text-decoration: none;">@<?= htmlspecialchars($user['bio_username']) ?></a>
                            <?php else: ?>
                                <span style="color: var(--muted);">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($user['is_blocked']): ?>
                                <span class="badge badge-danger">مسدود</span>
                            <?php else: ?>
                                <span class="badge badge-success">فعال</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($user['role'] !== 'admin'): ?>
                                <div style="display: flex; gap: 0.5rem;">
                                    <form method="POST" action="/admin/users/toggle-seller" style="display: inline;">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <button type="submit" class="btn" style="padding: 0.35rem 0.6rem; font-size: 0.8rem;">
                                            <?= $user['role'] === 'seller' ? 'تنزل به کاربر عادی' : 'ارتقا به فروشنده ⭐' ?>
                                        </button>
                                    </form>

                                    <form method="POST" action="/admin/users/toggle-block" style="display: inline;">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <button type="submit" class="btn <?= $user['is_blocked'] ? 'btn-success' : 'btn-danger' ?>" style="padding: 0.35rem 0.6rem; font-size: 0.8rem;">
                                            <?= $user['is_blocked'] ? 'رفع مسدودی' : 'مسدودسازی' ?>
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
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
