<?php $baseUrl = \App\Core\Security::getBaseUrl(); ?>
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 1.5rem; color: var(--color-text);"><?= \App\Core\Security::escape($pageTitle) ?></h1>
</div>

<div class="card" style="max-width: 800px;">
    <form method="POST" action="<?= $page ? $baseUrl . '/admin/pages/update/' . $page['id'] : $baseUrl . '/admin/pages/store' ?>" style="display: flex; flex-direction: column; gap: 1.25rem;">
        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::escape($csrfToken) ?>">

        <div>
            <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">عنوان صفحه:</label>
            <input type="text" name="title" value="<?= \App\Core\Security::escape($page['title'] ?? '') ?>" required style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
        </div>

        <div>
            <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">نام مستعار (Slug):</label>
            <input type="text" name="slug" value="<?= \App\Core\Security::escape($page['slug'] ?? '') ?>" required placeholder="about-us" style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
        </div>

        <div>
            <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">محتوای اصلی (Semantic HTML):</label>
            <textarea name="content" rows="10" required style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text); font-family: inherit;"><?= \App\Core\Security::escape($page['content'] ?? '') ?></textarea>
        </div>

        <div class="grid grid-cols-2" style="gap: 1rem;">
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">عنوان سئو (Meta Title):</label>
                <input type="text" name="meta_title" value="<?= \App\Core\Security::escape($page['meta_title'] ?? '') ?>" style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">وضعیت انتشار:</label>
                <select name="status" style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
                    <option value="published" <?= ($page['status'] ?? '') === 'published' ? 'selected' : '' ?>>منتشر شده</option>
                    <option value="draft" <?= ($page['status'] ?? '') === 'draft' ? 'selected' : '' ?>>پیش‌نویس</option>
                </select>
            </div>
        </div>

        <div>
            <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">توضیحات سئو (Meta Description):</label>
            <textarea name="meta_description" rows="3" style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text); font-family: inherit;"><?= \App\Core\Security::escape($page['meta_description'] ?? '') ?></textarea>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
            <button type="submit" class="btn btn-primary">ذخیره صفحه</button>
            <a href="<?= $baseUrl ?>/admin/pages" class="btn btn-outline">انصراف</a>
        </div>
    </form>
</div>
