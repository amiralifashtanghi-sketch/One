<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
    <div>
        <h1 style="margin:0;">مدیریت محصولات دیجیتال (Plugins & Themes)</h1>
        <p style="margin:5px 0 0 0; color:var(--eafd-color-text-muted);">تعریف محصولات، اتصال به فایل‌های تحویل امن و تعیین سیاست لایسنس</p>
    </div>
    <div>
        <a href="/admin/products/create" class="btn btn-primary">+ افزودن محصول جدید</a>
    </div>
</div>

<div class="card">
    <table class="install-table" style="width:100%;">
        <thead>
            <tr>
                <th>عنوان محصول</th>
                <th>نوع</th>
                <th>نسخه</th>
                <th>قیمت</th>
                <th>فایل محافظت‌شده</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($products)): ?>
                <tr><td colspan="6" style="text-align:center;">هیچ محصولی ثبت نشده است.</td></tr>
            <?php else: ?>
                <?php foreach ($products as $prod): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($prod['title']) ?></strong></td>
                        <td><code><?= htmlspecialchars($prod['type']) ?></code></td>
                        <td><?= htmlspecialchars($prod['version']) ?></td>
                        <td><?= number_format($prod['price']) ?> تومان</td>
                        <td><code><?= htmlspecialchars($prod['file_path']) ?></code></td>
                        <td>
                            <a href="/admin/products/edit/<?= $prod['id'] ?>" class="btn btn-secondary" style="padding:4px 10px; font-size:0.85rem;">ویرایش</a>
                            <a href="/admin/products/delete/<?= $prod['id'] ?>" class="btn btn-secondary" style="padding:4px 10px; font-size:0.85rem; color:#fca5a5;" onclick="return confirm('آیا از حذف این محصول اطمینان دارید؟');">حذف</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
