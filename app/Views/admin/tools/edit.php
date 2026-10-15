<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
    <div>
        <h1 style="margin:0;"><?= $tool ? 'ویرایش ابزار: ' . htmlspecialchars($tool['title']) : 'افزودن ابزار جدید' ?></h1>
    </div>
    <div>
        <a href="/admin/tools" class="btn btn-secondary">← بازگشت به لیست ابزارها</a>
    </div>
</div>

<div class="card" style="max-width:800px;">
    <form method="POST" action="/admin/tools/save/<?= $tool['id'] ?? 0 ?>">
        <input type="hidden" name="_csrf_token" value="<?= \App\Core\Csrf::generate() ?>">

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">عنوان ابزار:</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($tool['title'] ?? '') ?>" required>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">نوع ابزار:</label>
            <select name="tool_type" class="form-control">
                <option value="contrast" <?= ($tool['tool_type'] ?? '') === 'contrast' ? 'selected' : '' ?>>محاسبه‌گر کنتراست WCAG 2.2</option>
                <option value="generator" <?= ($tool['tool_type'] ?? '') === 'generator' ? 'selected' : '' ?>>تولیدکننده رمز عبور ایمن</option>
            </select>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">توضیحات ابزار:</label>
            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($tool['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">تنظیمات (JSON Config):</label>
            <textarea name="config" class="form-control" rows="4"><?= htmlspecialchars($tool['config'] ?? '{}') ?></textarea>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">وضعیت نمایش:</label>
            <select name="is_active" class="form-control">
                <option value="1" <?= ($tool['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>فعال</option>
                <option value="0" <?= ($tool['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>غیرفعال</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;">ذخیره اطلاعات ابزار</button>
    </form>
</div>
