<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
    <div>
        <h1 style="margin:0;">صفحه‌ساز اختصاصی: <?= htmlspecialchars($page['title']) ?></h1>
        <p style="margin:5px 0 0 0; color:var(--eafd-color-text-muted);">مدیریت چیدمان، ویرایش محتوا، افزودن کامپوننت و تاریخچه نسخه‌ها</p>
    </div>
    <div>
        <a href="/admin/pages" class="btn btn-secondary">← بازگشت به لیست برگه‌ها</a>
    </div>
</div>

<div class="grid grid-cols-3" style="gap:30px;">
    <!-- Main Builder List (2 cols) -->
    <div style="grid-column: span 2;">
        <form method="POST" action="/admin/pages/<?= $page['id'] ?>/builder/save">
            <input type="hidden" name="_csrf_token" value="<?= \App\Core\Csrf::generate() ?>">

            <?php if (empty($sections)): ?>
                <div class="card" style="text-align:center; padding:40px;">
                    <p>هیچ بخش یا کامپوننتی برای این برگه تعریف نشده است. از پنل سمت چپ اولین بخش را اضافه نمایید.</p>
                </div>
            <?php else: ?>
                <?php foreach ($sections as $index => $sec):
                    $settings = json_decode($sec['settings'] ?? '{}', true);
                    $schema = $schemas[$sec['section_type']] ?? null;
                ?>
                    <div class="card builder-section-item" style="margin-bottom:20px; border-right:4px solid var(--eafd-color-primary);">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; border-bottom:1px solid var(--eafd-color-border); padding-bottom:10px;">
                            <h3 style="margin:0; font-size:1.1rem; color:var(--eafd-color-secondary);">
                                <?= $index + 1 ?>. <?= htmlspecialchars($schema['name'] ?? $sec['section_type']) ?>
                            </h3>
                            <form method="POST" action="/admin/pages/<?= $page['id'] ?>/builder/delete-section/<?= $sec['id'] ?>" style="display:inline;" onsubmit="return confirm('آیا از حذف این بخش اطمینان دارید؟');">
                                <input type="hidden" name="_csrf_token" value="<?= \App\Core\Csrf::generate() ?>">
                                <button type="submit" class="btn btn-secondary" style="padding:4px 10px; color:#fca5a5;">حذف بخش</button>
                            </form>
                        </div>

                        <input type="hidden" name="sections[<?= $index ?>][id]" value="<?= $sec['id'] ?>">
                        <input type="hidden" name="sections[<?= $index ?>][type]" value="<?= htmlspecialchars($sec['section_type']) ?>">

                        <?php if ($schema && isset($schema['fields'])): ?>
                            <?php foreach ($schema['fields'] as $fieldKey => $fieldMeta): ?>
                                <div class="form-group" style="margin-bottom:15px;">
                                    <label style="display:block; margin-bottom:5px; font-weight:bold;"><?= htmlspecialchars($fieldMeta['label']) ?>:</label>
                                    <?php if ($fieldMeta['type'] === 'textarea'): ?>
                                        <textarea class="form-control" name="sections[<?= $index ?>][settings][<?= $fieldKey ?>]" rows="3"><?= htmlspecialchars($settings[$fieldKey] ?? '') ?></textarea>
                                    <?php else: ?>
                                        <input type="text" class="form-control" name="sections[<?= $index ?>][settings][<?= $fieldKey ?>]" value="<?= htmlspecialchars($settings[$fieldKey] ?? '') ?>">
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <button type="submit" class="btn btn-primary" style="width:100%; margin-top:10px;">ذخیره و انتشار نهایی چیدمان برگه</button>
            <?php endif; ?>
        </form>
    </div>

    <!-- Sidebar Add Section & Revisions (1 col) -->
    <div>
        <!-- Add Section Card -->
        <div class="card" style="margin-bottom:30px;">
            <h3 style="font-size:1.2rem; margin-bottom:15px;">افزودن بخش جدید</h3>
            <form method="POST" action="/admin/pages/<?= $page['id'] ?>/builder/add-section">
                <input type="hidden" name="_csrf_token" value="<?= \App\Core\Csrf::generate() ?>">
                <div class="form-group" style="margin-bottom:15px;">
                    <label style="display:block; margin-bottom:5px;">انتخاب نوع کامپوننت:</label>
                    <select name="section_type" class="form-control">
                        <?php foreach ($schemas as $key => $sch): ?>
                            <option value="<?= $key ?>"><?= htmlspecialchars($sch['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-secondary" style="width:100%;">+ افزودن بخش به انتهای برگه</button>
            </form>
        </div>

        <!-- Revisions History Card -->
        <div class="card">
            <h3 style="font-size:1.2rem; margin-bottom:15px;">تاریخچه نسخه‌ها و پشتیبان</h3>
            <?php if (empty($revisions)): ?>
                <p style="font-size:0.9rem; color:var(--eafd-color-text-muted);">هنوز هیچ پشتیبانی ثبت نشده است.</p>
            <?php else: ?>
                <ul style="list-style:none; padding:0; margin:0;">
                    <?php foreach ($revisions as $rev): ?>
                        <li style="padding:10px 0; border-bottom:1px solid var(--eafd-color-border); font-size:0.85rem; display:flex; justify-content:space-between; align-items:center;">
                            <span>نسخه مورخ <?= htmlspecialchars($rev['created_at']) ?></span>
                            <span class="badge badge-success">ذخیره‌شده</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
