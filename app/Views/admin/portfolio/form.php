<?php $baseUrl = \App\Core\Security::getBaseUrl(); ?>
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 1.5rem; color: var(--color-text);"><?= \App\Core\Security::escape($pageTitle) ?></h1>
</div>

<div class="card" style="max-width: 800px;">
    <form method="POST" action="<?= $item ? $baseUrl . '/admin/portfolio/update/' . $item['id'] : $baseUrl . '/admin/portfolio/store' ?>" style="display: flex; flex-direction: column; gap: 1.25rem;">
        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::escape($csrfToken) ?>">

        <div class="grid grid-cols-2" style="gap: 1rem;">
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">عنوان پروژه:</label>
                <input type="text" name="title" value="<?= \App\Core\Security::escape($item['title'] ?? '') ?>" required style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">نام مستعار (Slug):</label>
                <input type="text" name="slug" value="<?= \App\Core\Security::escape($item['slug'] ?? '') ?>" required placeholder="custom-portal" style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>
        </div>

        <div class="grid grid-cols-3" style="gap: 1rem;">
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">نام مشتری / برند:</label>
                <input type="text" name="client_name" value="<?= \App\Core\Security::escape($item['client_name'] ?? '') ?>" style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">دسته‌بندی خدمت:</label>
                <input type="text" name="service_type" value="<?= \App\Core\Security::escape($item['service_type'] ?? '') ?>" required placeholder="طراحی سایت اختصاصی" style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">تکنولوژی‌های استفاده شده:</label>
                <input type="text" name="technologies" value="<?= \App\Core\Security::escape($item['technologies'] ?? '') ?>" required placeholder="PHP 8.2, MySQL, CSS Grid" style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>
        </div>

        <div>
            <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">خلاصه اجرایی پروژه:</label>
            <textarea name="summary" rows="3" required style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text); font-family: inherit;"><?= \App\Core\Security::escape($item['summary'] ?? '') ?></textarea>
        </div>

        <div>
            <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">چالش کارفرمای پروژه (The Challenge):</label>
            <textarea name="challenge" rows="4" style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text); font-family: inherit;"><?= \App\Core\Security::escape($item['challenge'] ?? '') ?></textarea>
        </div>

        <div>
            <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">راهکار مهندسی EAFD (The Solution):</label>
            <textarea name="solution" rows="4" style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text); font-family: inherit;"><?= \App\Core\Security::escape($item['solution'] ?? '') ?></textarea>
        </div>

        <div>
            <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">نتایج و متریک‌های ملموس (Measurable Results):</label>
            <textarea name="results" rows="3" placeholder="افت زمان بارگذاری به زیر ۳۰۰ میلی‌ثانیه، افزایش ۴۰٪ نرخ تبدیل" style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text); font-family: inherit;"><?= \App\Core\Security::escape($item['results'] ?? '') ?></textarea>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
            <button type="submit" class="btn btn-primary">ذخیره پروژه</button>
            <a href="<?= $baseUrl ?>/admin/portfolio" class="btn btn-outline">انصراف</a>
        </div>
    </form>
</div>
