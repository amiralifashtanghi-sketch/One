<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
    <div>
        <h1 style="margin:0;"><?= $project ? 'ویرایش پروژه: ' . htmlspecialchars($project['title']) : 'افزودن پروژه جدید' ?></h1>
    </div>
    <div>
        <a href="/admin/projects" class="btn btn-secondary">← بازگشت به لیست پروژه‌ها</a>
    </div>
</div>

<div class="card" style="max-width:800px;">
    <form method="POST" action="/admin/projects/save/<?= $project['id'] ?? 0 ?>">
        <input type="hidden" name="_csrf_token" value="<?= \App\Core\Csrf::generate() ?>">

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">عنوان پروژه:</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($project['title'] ?? '') ?>" required>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">نام کارفرما / برند:</label>
            <input type="text" name="client_name" class="form-control" value="<?= htmlspecialchars($project['client_name'] ?? '') ?>">
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">تکنولوژی‌های استفاده‌شده:</label>
            <input type="text" name="technologies" class="form-control" value="<?= htmlspecialchars($project['technologies'] ?? 'PHP 8.2, MySQL, Vanilla JS') ?>">
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">خلاصه پروژه:</label>
            <textarea name="summary" class="form-control" rows="3"><?= htmlspecialchars($project['summary'] ?? '') ?></textarea>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">چالش پروژه (Challenge):</label>
            <textarea name="challenge" class="form-control" rows="3"><?= htmlspecialchars($project['challenge'] ?? '') ?></textarea>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">راهکار پیاده‌سازی‌شده (Solution):</label>
            <textarea name="solution" class="form-control" rows="3"><?= htmlspecialchars($project['solution'] ?? '') ?></textarea>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">نتایج حاصله (Results):</label>
            <textarea name="results" class="form-control" rows="3"><?= htmlspecialchars($project['results'] ?? '') ?></textarea>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">وضعیت نمایش:</label>
            <select name="is_active" class="form-control">
                <option value="1" <?= ($project['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>فعال و منتشرشده</option>
                <option value="0" <?= ($project['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>غیرفعال</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;">ذخیره پروژه</button>
    </form>
</div>
