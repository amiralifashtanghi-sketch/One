<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
    <div>
        <h1 style="margin:0;">مدیریت نمونه پروژه‌ها (Portfolio Case Studies)</h1>
        <p style="margin:5px 0 0 0; color:var(--eafd-color-text-muted);">ثبت و ویرایش نمونه پروژه‌های اجرایی کاملاً تصویر-آزاد (Image-Free Design System)</p>
    </div>
    <div>
        <a href="/admin/projects/create" class="btn btn-primary">+ افزودن پروژه جدید</a>
    </div>
</div>

<div class="card">
    <table class="install-table" style="width:100%;">
        <thead>
            <tr>
                <th>عنوان پروژه</th>
                <th>نام کارفرما</th>
                <th>تکنولوژی‌ها</th>
                <th>وضعیت</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($projects)): ?>
                <tr>
                    <td colspan="5" style="text-align:center;">هیچ پروژه‌ای ثبت نشده است.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($projects as $prj): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($prj['title']) ?></strong></td>
                        <td><?= htmlspecialchars($prj['client_name']) ?></td>
                        <td><small><?= htmlspecialchars($prj['technologies']) ?></small></td>
                        <td>
                            <?php if ($prj['is_active']): ?>
                                <span class="badge badge-success">فعال</span>
                            <?php else: ?>
                                <span class="badge badge-danger">غیرفعال</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="/admin/projects/edit/<?= $prj['id'] ?>" class="btn btn-secondary" style="padding:4px 10px; font-size:0.85rem;">ویرایش</a>
                            <a href="/admin/projects/delete/<?= $prj['id'] ?>" class="btn btn-secondary" style="padding:4px 10px; font-size:0.85rem; color:#fca5a5;" onclick="return confirm('آیا از حذف این پروژه اطمینان دارید؟');">حذف</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
