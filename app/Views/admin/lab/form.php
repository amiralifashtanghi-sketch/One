<?php $baseUrl = \App\Core\Security::getBaseUrl(); ?>
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 1.5rem; color: var(--color-text);"><?= \App\Core\Security::escape($pageTitle) ?></h1>
</div>

<div class="card" style="max-width: 800px;">
    <form method="POST" action="<?= $lab ? $baseUrl . '/admin/lab/update/' . $lab['id'] : $baseUrl . '/admin/lab/store' ?>" style="display: flex; flex-direction: column; gap: 1.25rem;">
        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::escape($csrfToken) ?>">

        <div class="grid grid-cols-2" style="gap: 1rem;">
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">عنوان پروژه LAB:</label>
                <input type="text" name="title" value="<?= \App\Core\Security::escape($lab['title'] ?? '') ?>" required style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">تکنولوژی اصلی:</label>
                <input type="text" name="technology" value="<?= \App\Core\Security::escape($lab['technology'] ?? '') ?>" required placeholder="Native WebGL / Canvas API" style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>
        </div>

        <div>
            <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">توضیحات و اهداف آزمایشگاه:</label>
            <textarea name="description" rows="4" required style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text); font-family: inherit;"><?= \App\Core\Security::escape($lab['description'] ?? '') ?></textarea>
        </div>

        <div class="grid grid-cols-3" style="gap: 1rem;">
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">وضعیت پروژه:</label>
                <select name="status" style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
                    <option value="live" <?= ($lab['status'] ?? '') === 'live' ? 'selected' : '' ?>>Live (عملیاتی)</option>
                    <option value="beta" <?= ($lab['status'] ?? '') === 'beta' ? 'selected' : '' ?>>Beta (آزمایشی)</option>
                    <option value="experimental" <?= ($lab['status'] ?? '') === 'experimental' ? 'selected' : '' ?>>Experimental (تجربی)</option>
                    <option value="archived" <?= ($lab['status'] ?? '') === 'archived' ? 'selected' : '' ?>>Archived (آرشیو)</option>
                </select>
            </div>
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">لینک دمو (Demo URL):</label>
                <input type="text" name="demo_url" value="<?= \App\Core\Security::escape($lab['demo_url'] ?? '') ?>" placeholder="https://..." style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">لینک گیت‌هاب (GitHub):</label>
                <input type="text" name="github_url" value="<?= \App\Core\Security::escape($lab['github_url'] ?? '') ?>" placeholder="https://github.com/..." style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
            <button type="submit" class="btn btn-primary">ذخیره پروژه LAB</button>
            <a href="<?= $baseUrl ?>/admin/lab" class="btn btn-outline">انصراف</a>
        </div>
    </form>
</div>
