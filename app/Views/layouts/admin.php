<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= \App\Core\Security::escape($pageTitle ?? 'پنل مدیریت اختصاصی EAFD') ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body style="background: #05070a;">
    <div style="display: flex; min-height: 100vh;">
        <!-- Admin Sidebar -->
        <aside style="width: 260px; background: var(--color-surface); border-left: 1px solid var(--color-surface-border); padding: 1.5rem 1rem; display: flex; flex-direction: column;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid var(--color-surface-border);">
                <svg width="28" height="28" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="36" height="36" rx="8" fill="#0E121A" stroke="#00F0FF" stroke-width="1.5"/>
                    <path d="M10 12H26M10 18H22M10 24H26" stroke="#00F0FF" stroke-width="2.5" stroke-linecap="round"/>
                    <circle cx="26" cy="18" r="2.5" fill="#FF8A00"/>
                </svg>
                <span style="font-weight: 800; font-size: 1.1rem; color: var(--color-primary);">مدیریت EAFD</span>
            </div>

            <nav style="flex: 1;">
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.5rem;">
                    <li><a href="/admin" style="display: block; padding: 0.6rem 1rem; border-radius: var(--radius-md); color: var(--color-text); font-size: 0.9rem;">داشبورد</a></li>
                    <li><a href="/admin/pages" style="display: block; padding: 0.6rem 1rem; border-radius: var(--radius-md); color: var(--color-text-muted); font-size: 0.9rem;">مدیریت صفحات</a></li>
                    <li><a href="/admin/services" style="display: block; padding: 0.6rem 1rem; border-radius: var(--radius-md); color: var(--color-text-muted); font-size: 0.9rem;">مدیریت خدمات</a></li>
                    <li><a href="/admin/portfolio" style="display: block; padding: 0.6rem 1rem; border-radius: var(--radius-md); color: var(--color-text-muted); font-size: 0.9rem;">نمونه‌کارها</a></li>
                    <li><a href="/admin/lab" style="display: block; padding: 0.6rem 1rem; border-radius: var(--radius-md); color: var(--color-text-muted); font-size: 0.9rem;">آزمایشگاه (LAB)</a></li>
                    <li><a href="/admin/leads" style="display: block; padding: 0.6rem 1rem; border-radius: var(--radius-md); color: var(--color-text-muted); font-size: 0.9rem;">درخواست‌های پروژه</a></li>
                    <li><a href="/admin/faq" style="display: block; padding: 0.6rem 1rem; border-radius: var(--radius-md); color: var(--color-text-muted); font-size: 0.9rem;">سوالات متداول (FAQ)</a></li>
                    <li><a href="/admin/seo" style="display: block; padding: 0.6rem 1rem; border-radius: var(--radius-md); color: var(--color-text-muted); font-size: 0.9rem;">تنظیمات سئو</a></li>
                    <li><a href="/admin/health" style="display: block; padding: 0.6rem 1rem; border-radius: var(--radius-md); color: var(--color-text-muted); font-size: 0.9rem;">سلامت سیستم</a></li>
                </ul>
            </nav>

            <div style="border-top: 1px solid var(--color-surface-border); padding-top: 1rem; margin-top: 1rem;">
                <a href="/admin/logout" class="btn btn-outline" style="width: 100%; text-align: center; font-size: 0.85rem;">خروج از پنل</a>
            </div>
        </aside>

        <!-- Admin Main Content -->
        <main style="flex: 1; padding: 2rem; overflow-y: auto;">
            <?= $content ?>
        </main>
    </div>
</body>
</html>
