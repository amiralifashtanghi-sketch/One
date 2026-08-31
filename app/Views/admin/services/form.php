<?php $baseUrl = \App\Core\Security::getBaseUrl(); ?>
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 1.5rem; color: var(--color-text);"><?= \App\Core\Security::escape($pageTitle) ?></h1>
</div>

<div class="card" style="max-width: 800px;">
    <form method="POST" action="<?= $service ? $baseUrl . '/admin/services/update/' . $service['id'] : $baseUrl . '/admin/services/store' ?>" style="display: flex; flex-direction: column; gap: 1.25rem;">
        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::escape($csrfToken) ?>">

        <div class="grid grid-cols-2" style="gap: 1rem;">
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">عنوان خدمت:</label>
                <input type="text" name="title" value="<?= \App\Core\Security::escape($service['title'] ?? '') ?>" required style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">نام مستعار (Slug):</label>
                <input type="text" name="slug" value="<?= \App\Core\Security::escape($service['slug'] ?? '') ?>" required placeholder="custom-web" style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>
        </div>

        <div>
            <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">توضیحات کوتاه:</label>
            <textarea name="short_description" rows="3" required style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text); font-family: inherit;"><?= \App\Core\Security::escape($service['short_description'] ?? '') ?></textarea>
        </div>

        <div>
            <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">توضیحات کامل خدمت:</label>
            <textarea name="full_description" rows="8" required style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text); font-family: inherit;"><?= \App\Core\Security::escape($service['full_description'] ?? '') ?></textarea>
        </div>

        <div>
            <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">آیکون SVG اختصاصی:</label>
            <textarea name="icon_svg" rows="3" placeholder="<svg ...></svg>" style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text); font-family: monospace;"><?= \App\Core\Security::escape($service['icon_svg'] ?? '') ?></textarea>
        </div>

        <div class="grid grid-cols-2" style="gap: 1rem; align-items: center;">
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">ترتیب نمایش:</label>
                <input type="number" name="sort_order" value="<?= $service['sort_order'] ?? 1 ?>" style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 1.5rem;">
                <input type="checkbox" name="is_active" id="is_active" value="1" <?= (!isset($service) || !empty($service['is_active'])) ? 'checked' : '' ?>>
                <label for="is_active" style="font-size: 0.875rem; color: var(--color-text);">خدمت فعال باشد</label>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
            <button type="submit" class="btn btn-primary">ذخیره خدمت</button>
            <a href="<?= $baseUrl ?>/admin/services" class="btn btn-outline">انصراف</a>
        </div>
    </form>
</div>
