<?php $baseUrl = \App\Core\Security::getBaseUrl(); ?>
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 1.5rem; color: var(--color-text);">تنظیمات سئو و متاداده‌های اصلی EAFD</h1>
</div>

<div class="card" style="max-width: 700px;">
    <form method="POST" action="<?= $baseUrl ?>/admin/seo/update" style="display: flex; flex-direction: column; gap: 1.25rem;">
        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::escape($csrfToken) ?>">

        <div>
            <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">عنوان عمومی وب‌سایت (Site Title):</label>
            <input type="text" name="site_title" value="<?= \App\Core\Security::escape($settings['site_title'] ?? '') ?>" required style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
        </div>

        <div>
            <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">توضیحات متای پیش‌فرض (Default Meta Description):</label>
            <textarea name="site_description" rows="3" required style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text); font-family: inherit;"><?= \App\Core\Security::escape($settings['site_description'] ?? '') ?></textarea>
        </div>

        <div class="grid grid-cols-2" style="gap: 1rem;">
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">ایمیل تماس عمومی:</label>
                <input type="email" name="contact_email" value="<?= \App\Core\Security::escape($settings['contact_email'] ?? '') ?>" style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">شماره تماس ارتباطی:</label>
                <input type="text" name="contact_phone" value="<?= \App\Core\Security::escape($settings['contact_phone'] ?? '') ?>" style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">ذخیره تنظیمات سئو</button>
    </form>
</div>
