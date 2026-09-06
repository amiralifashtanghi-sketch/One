<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
    <div>
        <h1 style="margin:0;">مدیریت آزمون‌های آنلاین (LAB Quizzes)</h1>
        <p style="margin:5px 0 0 0; color:var(--eafd-color-text-muted);">تعریف و ویرایش آزمون‌های تعاملی و سنجش آمادگی وب پلتفرم</p>
    </div>
    <div>
        <a href="/admin/quizzes/create" class="btn btn-primary">+ افزودن آزمون جدید</a>
    </div>
</div>

<div class="card">
    <table class="install-table" style="width:100%;">
        <thead>
            <tr>
                <th>عنوان آزمون</th>
                <th>اسلاگ (URL)</th>
                <th>وضعیت</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($quizzes)): ?>
                <tr><td colspan="4" style="text-align:center;">هیچ آزمونی ثبت نشده است.</td></tr>
            <?php else: ?>
                <?php foreach ($quizzes as $quiz): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($quiz['title']) ?></strong></td>
                        <td><code><?= htmlspecialchars($quiz['slug']) ?></code></td>
                        <td>
                            <?php if ($quiz['is_active']): ?>
                                <span class="badge badge-success">فعال</span>
                            <?php else: ?>
                                <span class="badge badge-danger">غیرفعال</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="/admin/quizzes/edit/<?= $quiz['id'] ?>" class="btn btn-secondary" style="padding:4px 10px; font-size:0.85rem;">ویرایش</a>
                            <a href="/admin/quizzes/delete/<?= $quiz['id'] ?>" class="btn btn-secondary" style="padding:4px 10px; font-size:0.85rem; color:#fca5a5;" onclick="return confirm('آیا از حذف این آزمون اطمینان دارید؟');">حذف</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
