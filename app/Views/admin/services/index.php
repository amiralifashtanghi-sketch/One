<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
    <div>
        <h1 style="margin:0;">مدیریت خدمات تخصصی</h1>
        <p style="margin:5px 0 0 0; color:var(--eafd-color-text-muted);">تعریف و ویرایش لیست خدمات وب‌سایت با قابلیت تنظیم قیمت پایه و ویژگی‌ها</p>
    </div>
    <div>
        <a href="/admin/services/create" class="btn btn-primary">+ افزودن خدمت جدید</a>
    </div>
</div>

<div class="card">
    <table class="install-table" style="width:100%;">
        <thead>
            <tr>
                <th>آیکون</th>
                <th>عنوان خدمت</th>
                <th>اسلاگ (URL)</th>
                <th>شروع قیمت</th>
                <th>وضعیت</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($services)): ?>
                <tr>
                    <td colspan="6" style="text-align:center;">هیچ خدمتی ثبت نشده است.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($services as $srv): ?>
                    <tr>
                        <td style="font-size:1.5rem; text-align:center;"><?= $srv['icon'] ?? '⚡' ?></td>
                        <td><strong><?= htmlspecialchars($srv['title']) ?></strong></td>
                        <td><code><?= htmlspecialchars($srv['slug']) ?></code></td>
                        <td><?= htmlspecialchars($srv['price_start']) ?></td>
                        <td>
                            <?php if ($srv['is_active']): ?>
                                <span class="badge badge-success">فعال</span>
                            <?php else: ?>
                                <span class="badge badge-danger">غیرفعال</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="/admin/services/edit/<?= $srv['id'] ?>" class="btn btn-secondary" style="padding:4px 10px; font-size:0.85rem;">ویرایش</a>
                            <a href="/admin/services/delete/<?= $srv['id'] ?>" class="btn btn-secondary" style="padding:4px 10px; font-size:0.85rem; color:#fca5a5;" onclick="return confirm('آیا از حذف این خدمت اطمینان دارید؟');">حذف</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
