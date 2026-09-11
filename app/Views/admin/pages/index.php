<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
    <div>
        <h1 style="margin:0;">مدیریت برگه‌ها</h1>
        <p style="margin:5px 0 0 0; color:var(--eafd-color-text-muted);">تعریف برگه‌ها و ورود به محیط صفحه‌ساز مدولار EAFD</p>
    </div>
    <div>
        <a href="/admin/pages/create" class="btn btn-primary">+ ایجاد برگه جدید</a>
    </div>
</div>

<div class="card">
    <table class="install-table" style="width:100%;">
        <thead>
            <tr>
                <th>عنوان برگه</th>
                <th>اسلاگ (URL)</th>
                <th>وضعیت انتشار</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pages as $p): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($p['title']) ?></strong></td>
                    <td><code>/<?= htmlspecialchars($p['slug']) ?></code></td>
                    <td>
                        <?php if ($p['is_published']): ?>
                            <span class="badge badge-success">منتشرشده</span>
                        <?php else: ?>
                            <span class="badge badge-danger">پیش‌نویس</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="/admin/pages/<?= $p['id'] ?>/builder" class="btn btn-primary" style="padding:4px 10px; font-size:0.85rem;">🎨 صفحه‌ساز</a>
                        <a href="/admin/pages/edit/<?= $p['id'] ?>" class="btn btn-secondary" style="padding:4px 10px; font-size:0.85rem;">تنظیمات</a>
                        <a href="/admin/pages/delete/<?= $p['id'] ?>" class="btn btn-secondary" style="padding:4px 10px; font-size:0.85rem; color:#fca5a5;" onclick="return confirm('حذف برگه؟');">حذف</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
