<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
    <div>
        <h1 style="margin:0;"><?= $product ? 'ویرایش محصول: ' . htmlspecialchars($product['title']) : 'افزودن محصول جدید' ?></h1>
    </div>
    <div>
        <a href="/admin/products" class="btn btn-secondary">← بازگشت به لیست محصولات</a>
    </div>
</div>

<div class="card" style="max-width:800px;">
    <form method="POST" action="/admin/products/save/<?= $product['id'] ?? 0 ?>">
        <input type="hidden" name="_csrf_token" value="<?= \App\Core\Csrf::generate() ?>">

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">عنوان محصول:</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($product['title'] ?? '') ?>" required>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">قیمت (تومان):</label>
            <input type="number" name="price" class="form-control" value="<?= $product['price'] ?? 0 ?>" required>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">نوع محصول:</label>
            <select name="type" class="form-control">
                <option value="plugin" <?= ($product['type'] ?? '') === 'plugin' ? 'selected' : '' ?>>افزونه (Plugin)</option>
                <option value="theme" <?= ($product['type'] ?? '') === 'theme' ? 'selected' : '' ?>>قالب (Theme)</option>
            </select>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">نام فایل محصول در مسیر غیرعمومی storage/products/:</label>
            <input type="text" name="file_path" class="form-control" value="<?= htmlspecialchars($product['file_path'] ?? '') ?>" placeholder="مثلاً: eafd-sms-login.zip">
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">تعداد دامنه مجاز برای لایسنس:</label>
            <input type="number" name="max_domains" class="form-control" value="<?= $product['max_domains'] ?? 1 ?>">
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">توضیحات و مشخصات:</label>
            <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;">ذخیره اطلاعات محصول</button>
    </form>
</div>
