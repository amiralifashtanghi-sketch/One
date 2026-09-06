<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
    <div>
        <h1 style="margin:0;">مدیریت ابزارهای آنلاین (LAB Tools)</h1>
        <p style="margin:5px 0 0 0; color:var(--eafd-color-text-muted);">مدیریت ابزارهای محاسباتی و سنجش آنلاین پلتفرم EAFD</p>
    </div>
    <div>
        <a href="/admin/tools/create" class="btn btn-primary">+ افزودن ابزار جدید</a>
    </div>
</div>

<div class="card">
    <table class="install-table" style="width:100%;">
        <thead>
            <tr>
                <th>عنوان ابزار</th>
                <th>نوع ابزار</th>
                <th>اسلاگ (URL)</th>
                <th>وضعیت</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tools)): ?>
                <tr><td colspan="5" style="text-align:center;">هیچ ابزاری ثبت نشده است.</td></tr>
            <?php else: ?>
                <?php foreach ($tools as $tool): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($tool['title']) ?></strong></td>
                        <td><code><?= htmlspecialchars($tool['tool_type']) ?></code></td>
                        <td><code><?= htmlspecialchars($tool['slug']) ?></code></td>
                        <td>
                            <?php if ($tool['is_active']): ?>
                                <span class="badge badge-success">فعال</span>
                            <?php else: ?>
                                <span class="badge badge-danger">غیرفعال</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="/admin/tools/edit/<?= $tool['id'] ?>" class="btn btn-secondary" style="padding:4px 10px; font-size:0.85rem;">ویرایش</a>
                            <a href="/admin/tools/delete/<?= $tool['id'] ?>" class="btn btn-secondary" style="padding:4px 10px; font-size:0.85rem; color:#fca5a5;" onclick="return confirm('آیا از حذف این ابزار اطمینان دارید؟');">حذف</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
