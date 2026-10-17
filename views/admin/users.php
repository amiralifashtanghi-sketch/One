<?php
ob_start();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h2 style="margin: 0;">مدیریت کاربران و تعریف فروشندگان</h2>
    <button onclick="document.getElementById('addSellerModal').style.display='flex'" class="btn btn-success">+ تعریف فروشنده جدید</button>
</div>

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

<!-- Modal Add Seller -->
<div id="addSellerModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); align-items: center; justify-content: center; z-index: 1000; padding: 1rem;">
    <div style="background: var(--card-bg); border-radius: 16px; padding: 1.5rem; width: 100%; max-width: 450px; border: 1px solid var(--border); box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="margin:0;">تعریف فروشنده جدید</h3>
            <button type="button" onclick="document.getElementById('addSellerModal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text);">&times;</button>
        </div>

        <form method="POST" action="/admin/users/add-seller">
            <div class="form-group">
                <label>نام و نام خانوادگی فروشنده:</label>
                <input type="text" name="name" placeholder="مثال: محمد رضا فروشنده" required>
            </div>
            <div class="form-group">
                <label>شماره همراه (ورود):</label>
                <input type="text" name="phone" placeholder="۰۹۱۲۱۲۳۴۵۶۷" required>
            </div>
            <div class="form-group">
                <label>رمز عبور فروشنده:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-success" style="width: 100%; margin-top: 1rem;">ثبت و ایجاد حساب فروشنده</button>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
