<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
    <div>
        <h1 style="margin:0;"><?= $quiz ? 'ویرایش آزمون: ' . htmlspecialchars($quiz['title']) : 'افزودن آزمون جدید' ?></h1>
    </div>
    <div>
        <a href="/admin/quizzes" class="btn btn-secondary">← بازگشت به لیست آزمون‌ها</a>
    </div>
</div>

<div class="card" style="max-width:800px;">
    <form method="POST" action="/admin/quizzes/save/<?= $quiz['id'] ?? 0 ?>">
        <input type="hidden" name="_csrf_token" value="<?= \App\Core\Csrf::generate() ?>">

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">عنوان آزمون:</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($quiz['title'] ?? '') ?>" required>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">توضیحات آزمون:</label>
            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($quiz['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">سوالات آزمون (JSON Structure):</label>
            <textarea name="questions_json" class="form-control" rows="8" required><?= htmlspecialchars($quiz['questions_json'] ?? '[]') ?></textarea>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">وضعیت نمایش:</label>
            <select name="is_active" class="form-control">
                <option value="1" <?= ($quiz['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>فعال</option>
                <option value="0" <?= ($quiz['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>غیرفعال</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;">ذخیره اطلاعات آزمون</button>
    </form>
</div>
