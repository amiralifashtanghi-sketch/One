<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
    <div>
        <h1 style="margin:0;"><?= $service ? 'ویرایش خدمت: ' . htmlspecialchars($service['title']) : 'افزودن خدمت جدید' ?></h1>
    </div>
    <div>
        <a href="/admin/services" class="btn btn-secondary">← بازگشت به لیست خدمات</a>
    </div>
</div>

<div class="card" style="max-width:800px;">
    <form method="POST" action="/admin/services/save/<?= $service['id'] ?? 0 ?>">
        <input type="hidden" name="_csrf_token" value="<?= \App\Core\Csrf::generate() ?>">

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">عنوان خدمت:</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($service['title'] ?? '') ?>" required>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">اسلاگ آدرس (Slug):</label>
            <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($service['slug'] ?? '') ?>" placeholder="به‌صورت خودکار از عنوان تولید می‌شود">
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">آیکون (SVG یا ایموجی):</label>
            <input type="text" name="icon" class="form-control" value="<?= htmlspecialchars($service['icon'] ?? '🌐') ?>">
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">شروع قیمت:</label>
            <input type="text" name="price_start" class="form-control" value="<?= htmlspecialchars($service['price_start'] ?? '۲۵,۰۰۰,۰۰۰ تومان') ?>">
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">خلاصه معرفی:</label>
            <textarea name="summary" class="form-control" rows="3"><?= htmlspecialchars($service['summary'] ?? '') ?></textarea>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">شرح کامل خدمت:</label>
            <textarea name="content" class="form-control" rows="6"><?= htmlspecialchars($service['content'] ?? '') ?></textarea>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">وضعیت نمایش:</label>
            <select name="is_active" class="form-control">
                <option value="1" <?= ($service['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>فعال و منتشرشده</option>
                <option value="0" <?= ($service['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>غیرفعال</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;">ذخیره اطلاعات خدمت</button>
    </form>
</div>
