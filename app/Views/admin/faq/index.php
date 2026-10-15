<?php $baseUrl = \App\Core\Security::getBaseUrl(); ?>
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 1.5rem; color: var(--color-text);">مدیریت سوالات متداول (FAQ)</h1>
</div>

<div class="grid grid-cols-2" style="gap: 2rem;">
    <div class="card">
        <h2 style="font-size: 1.1rem; color: var(--color-text); margin-bottom: 1rem;">افزودن سوال جدید</h2>
        <form method="POST" action="<?= $baseUrl ?>/admin/faq/store" style="display: flex; flex-direction: column; gap: 1rem;">
            <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::escape($csrfToken) ?>">
            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--color-text); margin-bottom: 0.5rem;">پرسش:</label>
                <input type="text" name="question" required style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>
            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--color-text); margin-bottom: 0.5rem;">پاسخ کامل:</label>
                <textarea name="answer" rows="4" required style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text); font-family: inherit;"></textarea>
            </div>
            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--color-text); margin-bottom: 0.5rem;">ترتیب نمایش:</label>
                <input type="number" name="sort_order" value="1" style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>
            <button type="submit" class="btn btn-primary">ثبت سوال متداول</button>
        </form>
    </div>

    <div class="card">
        <h2 style="font-size: 1.1rem; color: var(--color-text); margin-bottom: 1rem;">سوالات متداول فعلی</h2>
        <?php if (empty($faqs)): ?>
            <p style="color: var(--color-text-muted); font-size: 0.875rem;">هیچ سوالی ثبت نشده است.</p>
        <?php else: ?>
            <ul style="list-style: none; display: flex; flex-direction: column; gap: 1rem;">
                <?php foreach ($faqs as $f): ?>
                    <li style="border-bottom: 1px solid var(--color-surface-border); padding-bottom: 1rem;">
                        <div style="font-weight: 600; color: var(--color-text); margin-bottom: 0.25rem;"><?= \App\Core\Security::escape($f['question']) ?></div>
                        <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 0.5rem;"><?= \App\Core\Security::escape($f['answer']) ?></p>
                        <a href="<?= $baseUrl ?>/admin/faq/delete/<?= $f['id'] ?>" onclick="return confirm('حذف شود؟');" style="color: var(--color-danger); font-size: 0.75rem;">حذف سوال</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>
