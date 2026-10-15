<?php $baseUrl = \App\Core\Security::getBaseUrl(); ?>
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 1.5rem; color: var(--color-text);"><?= \App\Core\Security::escape($pageTitle) ?></h1>
</div>

<div class="card" style="max-width: 700px;">
    <div style="display: flex; flex-direction: column; gap: 1rem; font-size: 0.95rem;">
        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--color-surface-border); padding-bottom: 0.75rem;">
            <span style="color: var(--color-text-muted);">نام و نام خانوادگی:</span>
            <strong style="color: var(--color-text);"><?= \App\Core\Security::escape($lead['name']) ?></strong>
        </div>
        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--color-surface-border); padding-bottom: 0.75rem;">
            <span style="color: var(--color-text-muted);">شماره تماس:</span>
            <strong style="color: var(--color-primary); font-family: monospace;"><?= \App\Core\Security::escape($lead['phone']) ?></strong>
        </div>
        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--color-surface-border); padding-bottom: 0.75rem;">
            <span style="color: var(--color-text-muted);">ایمیل:</span>
            <strong style="color: var(--color-text);"><?= \App\Core\Security::escape($lead['email'] ?? 'ثبت نشده') ?></strong>
        </div>
        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--color-surface-border); padding-bottom: 0.75rem;">
            <span style="color: var(--color-text-muted);">نوع پروژه:</span>
            <strong style="color: var(--color-text);"><?= \App\Core\Security::escape($lead['project_type']) ?></strong>
        </div>
        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--color-surface-border); padding-bottom: 0.75rem;">
            <span style="color: var(--color-text-muted);">بودجه در نظر گرفته‌شده:</span>
            <strong style="color: var(--color-accent);"><?= \App\Core\Security::escape($lead['budget'] ?? 'تعیین نشده') ?></strong>
        </div>
        <div>
            <span style="display: block; color: var(--color-text-muted); margin-bottom: 0.5rem;">توضیحات و پیام کاربر:</span>
            <div style="background: var(--color-bg); padding: 1rem; border-radius: var(--radius-md); color: var(--color-text); line-height: 1.8;">
                <?= nl2br(\App\Core\Security::escape($lead['message'])) ?>
            </div>
        </div>

        <form method="POST" action="<?= $baseUrl ?>/admin/leads/update-status/<?= $lead['id'] ?>" style="margin-top: 1.5rem; border-top: 1px solid var(--color-surface-border); padding-top: 1.5rem; display: flex; align-items: center; gap: 1rem;">
            <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::escape($csrfToken) ?>">
            <label style="font-size: 0.875rem; color: var(--color-text);">تغییر وضعیت درخواست:</label>
            <select name="status" style="padding: 0.5rem 1rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
                <option value="new" <?= $lead['status'] === 'new' ? 'selected' : '' ?>>جدید (New)</option>
                <option value="reviewing" <?= $lead['status'] === 'reviewing' ? 'selected' : '' ?>>در حال بررسی (Reviewing)</option>
                <option value="contacted" <?= $lead['status'] === 'contacted' ? 'selected' : '' ?>>تماس گرفته شد (Contacted)</option>
                <option value="won" <?= $lead['status'] === 'won' ? 'selected' : '' ?>>موفق / شروع کار (Won)</option>
                <option value="lost" <?= $lead['status'] === 'lost' ? 'selected' : '' ?>>ناموفق (Lost)</option>
            </select>
            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem;">بروزرسانی وضعیت</button>
        </form>
    </div>
</div>
