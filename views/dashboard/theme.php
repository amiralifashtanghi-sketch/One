<?php
ob_start();
?>

<h2 style="margin-top: 0; margin-bottom: 1.5rem;">سفارشی‌سازی ظاهر و تم رنگی بیو</h2>

<div class="card" style="max-width: 650px;">
    <form method="POST" action="/dashboard/theme">
        <div class="form-group">
            <label>انتخاب تم آماده:</label>
            <select name="theme">
                <option value="default" <?= ($bio['theme'] ?? '') === 'default' ? 'selected' : '' ?>>🌙 تاریک مدرن (Dark Tech)</option>
                <option value="ocean" <?= ($bio['theme'] ?? '') === 'ocean' ? 'selected' : '' ?>>🌊 آبی اقیانوسی (Ocean Gradient)</option>
                <option value="sunset" <?= ($bio['theme'] ?? '') === 'sunset' ? 'selected' : '' ?>>🌅 غروب طلایی (Sunset Orange/Purple)</option>
                <option value="emerald" <?= ($bio['theme'] ?? '') === 'emerald' ? 'selected' : '' ?>>🟢 زمردی و سبز لاکچری (Emerald Green)</option>
                <option value="purple" <?= ($bio['theme'] ?? '') === 'purple' ? 'selected' : '' ?>>🟣 بنفش گلاسمورفیک (Neon Purple)</option>
                <option value="minimal_light" <?= ($bio['theme'] ?? '') === 'minimal_light' ? 'selected' : '' ?>>☀️ روشن مینیمال (Minimal Light)</option>
            </select>
        </div>

        <div class="form-group">
            <label>کد استایل اختصاصی CSS (اختیاری):</label>
            <textarea name="custom_css" rows="5" placeholder=".bio-card { border-radius: 20px; }"><?= htmlspecialchars($bio['custom_css'] ?? '') ?></textarea>
            <div style="font-size: 0.8rem; color: var(--muted); margin-top: 0.25rem;">
                اگر دانش طراحی CSS دارید می‌توانید استایل دلخواه خود را اضافه کنید.
            </div>
        </div>

        <button type="submit" class="btn btn-success" style="width: 100%;">ذخیره تم و استایل</button>
    </form>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
