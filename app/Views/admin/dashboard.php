<?php $baseUrl = \App\Core\Security::getBaseUrl(); ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 1.75rem; color: var(--color-text);">داشبورد مدیریت EAFD</h1>
        <p style="font-size: 0.9rem; color: var(--color-text-muted);">خوش آمدید، <?= \App\Core\Security::escape($adminUser['display_name'] ?? 'مدیر') ?></p>
    </div>
    <div>
        <a href="<?= $baseUrl ?>/" target="_blank" class="btn btn-outline" style="font-size: 0.85rem;">مشاهده وب‌سایت اصلی ↗</a>
    </div>
</div>

<div class="grid grid-cols-4" style="margin-bottom: 2.5rem;">
    <div class="card">
        <span style="font-size: 0.8rem; color: var(--color-text-muted);">تعداد خدمات فعال</span>
        <div style="font-size: 2rem; font-weight: bold; color: var(--color-primary); margin-top: 0.5rem;"><?= $stats['services_count'] ?></div>
    </div>
    <div class="card">
        <span style="font-size: 0.8rem; color: var(--color-text-muted);">نمونه‌کارهای ثبت‌شده</span>
        <div style="font-size: 2rem; font-weight: bold; color: var(--color-text); margin-top: 0.5rem;"><?= $stats['portfolio_count'] ?></div>
    </div>
    <div class="card">
        <span style="font-size: 0.8rem; color: var(--color-text-muted);">درخواست‌های پروژه (Leads)</span>
        <div style="font-size: 2rem; font-weight: bold; color: var(--color-accent); margin-top: 0.5rem;"><?= $stats['leads_count'] ?></div>
    </div>
    <div class="card">
        <span style="font-size: 0.8rem; color: var(--color-text-muted);">شاخص سلامت سیستم</span>
        <div style="font-size: 2rem; font-weight: bold; color: var(--color-success); margin-top: 0.5rem;">۱۰۰٪</div>
    </div>
</div>

<div class="grid grid-cols-2" style="gap: 2rem;">
    <div class="card">
        <h2 style="font-size: 1.1rem; color: var(--color-text); margin-bottom: 1.25rem;">آخرین درخواست‌های پروژه</h2>
        <?php if (empty($recentLeads)): ?>
            <p style="font-size: 0.875rem; color: var(--color-text-dim);">هیچ درخواستی تاکنون ثبت نشده است.</p>
        <?php else: ?>
            <ul style="list-style: none; display: flex; flex-direction: column; gap: 1rem;">
                <?php foreach ($recentLeads as $lead): ?>
                    <li style="padding-bottom: 0.75rem; border-bottom: 1px border var(--color-surface-border); display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div style="font-weight: 600; font-size: 0.9rem; color: var(--color-text);"><?= \App\Core\Security::escape($lead['name']) ?> (<?= \App\Core\Security::escape($lead['project_type']) ?>)</div>
                            <div style="font-size: 0.75rem; color: var(--color-text-muted);"><?= \App\Core\Security::escape($lead['phone']) ?></div>
                        </div>
                        <span style="font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: var(--radius-sm); background: rgba(0, 240, 255, 0.1); color: var(--color-primary);">
                            <?= \App\Core\Security::escape($lead['status']) ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2 style="font-size: 1.1rem; color: var(--color-text); margin-bottom: 1.25rem;">وضعیت زیرساخت امنیتی EAFD</h2>
        <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.875rem; font-size: 0.875rem;">
            <li style="display: flex; justify-content: space-between;">
                <span style="color: var(--color-text-muted);">معماری وب‌سرور:</span>
                <span style="color: var(--color-success); font-weight: 600;">PHP <?= PHP_VERSION ?> Native MVC</span>
            </li>
            <li style="display: flex; justify-content: space-between;">
                <span style="color: var(--color-text-muted);">محافظت CSRF & XSS:</span>
                <span style="color: var(--color-success); font-weight: 600;">فعال (Argon2id + Strict Headers)</span>
            </li>
            <li style="display: flex; justify-content: space-between;">
                <span style="color: var(--color-text-muted);">قفل نصب‌کننده (Installer Lock):</span>
                <span style="color: var(--color-success); font-weight: 600;">فعال و ایمن</span>
            </li>
            <li style="display: flex; justify-content: space-between;">
                <span style="color: var(--color-text-muted);">پشتیبانی از ایجنت‌های هوشمند:</span>
                <span style="color: var(--color-primary); font-weight: 600;">Agentic Browsing 100%</span>
            </li>
        </ul>
    </div>
</div>
