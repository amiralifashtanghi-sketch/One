<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
    <div>
        <h1 style="margin:0;"><?= $page ? 'ویرایش تنظیمات برگه: ' . htmlspecialchars($page['title']) : 'ایجاد برگه جدید' ?></h1>
    </div>
    <div>
        <a href="/admin/pages" class="btn btn-secondary">← بازگشت به لیست برگه‌ها</a>
    </div>
</div>

<div class="card" style="max-width:700px;">
    <form method="POST" action="/admin/pages/save/<?= $page['id'] ?? 0 ?>">
        <input type="hidden" name="_csrf_token" value="<?= \App\Core\Csrf::generate() ?>">

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">عنوان برگه:</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($page['title'] ?? '') ?>" required>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">اسلاگ آدرس (Slug):</label>
            <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($page['slug'] ?? '') ?>">
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">خلاصه و توضیحات SEO:</label>
            <textarea name="summary" class="form-control" rows="3"><?= htmlspecialchars($page['summary'] ?? '') ?></textarea>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">وضعیت انتشار:</label>
            <select name="is_published" class="form-control">
                <option value="1" <?= ($page['is_published'] ?? 1) == 1 ? 'selected' : '' ?>>منتشرشده</option>
                <option value="0" <?= ($page['is_published'] ?? 1) == 0 ? 'selected' : '' ?>>پیش‌نویس</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;">ذخیره تنظیمات برگه</button>
    </form>
</div>
