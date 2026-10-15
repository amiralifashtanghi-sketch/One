<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
    <div>
        <h1 style="margin:0;">مدیریت کاربران و اپراتورها</h1>
        <p style="margin:5px 0 0 0; color:var(--eafd-color-text-muted);">تعیین نقش‌های کاربری، سطح دسترسی و فعال‌سازی حساب‌ها</p>
    </div>
</div>

<div class="card">
    <table class="install-table" style="width:100%;">
        <thead>
            <tr>
                <th>نام کاربر</th>
                <th>شماره موبایل</th>
                <th>ایمیل</th>
                <th>نقش دسترسی</th>
                <th>وضعیت</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($u['name']) ?></strong></td>
                    <td><code><?= htmlspecialchars($u['phone']) ?></code></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><span class="badge badge-success"><?= htmlspecialchars($u['role']) ?></span></td>
                    <td>
                        <?php if ($u['is_active']): ?>
                            <span class="badge badge-success">فعال</span>
                        <?php else: ?>
                            <span class="badge badge-danger">غیرفعال</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="/admin/users/edit/<?= $u['id'] ?>" class="btn btn-secondary" style="padding:4px 10px; font-size:0.85rem;">ویرایش دسترسی</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
