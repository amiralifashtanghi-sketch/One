<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'مدیریت سامانه EAFD', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/assets/css/design-tokens.css">
    <link rel="stylesheet" href="/assets/css/main.css">
    <style>
        .eafd-admin-wrapper {
            display: flex;
            min-height: 100vh;
            background-color: var(--eafd-color-bg, #080808);
            color: var(--eafd-color-text, #F5F5F5);
        }
        .eafd-admin-sidebar {
            width: 260px;
            background: rgba(18, 18, 20, 0.8);
            border-left: 1px solid var(--eafd-color-border, rgba(255, 255, 255, 0.08));
            padding: 1.5rem;
            flex-shrink: 0;
        }
        .eafd-admin-content {
            flex-grow: 1;
            padding: 2rem;
            overflow-x: auto;
        }
        .eafd-admin-brand {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--eafd-color-primary, #FFFFFF);
            text-decoration: none;
        }
        .eafd-admin-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .eafd-admin-menu a {
            display: block;
            padding: 0.75rem 1rem;
            color: var(--eafd-color-text-muted, #8A8A92);
            text-decoration: none;
            border-radius: var(--eafd-border-radius-sm, 6px);
            transition: all 0.2s ease;
        }
        .eafd-admin-menu a:hover,
        .eafd-admin-menu a.active {
            color: var(--eafd-color-text, #F5F5F5);
            background: rgba(255, 255, 255, 0.05);
        }
        @media (max-width: 768px) {
            .eafd-admin-wrapper {
                flex-direction: column;
            }
            .eafd-admin-sidebar {
                width: 100%;
                border-left: none;
                border-bottom: 1px solid var(--eafd-color-border, rgba(255, 255, 255, 0.08));
                padding: 1rem;
            }
            .eafd-admin-content {
                padding: 1rem;
            }
        }
    </style>
</head>
<body class="eafd-body">
    <div class="eafd-admin-wrapper">
        <aside class="eafd-admin-sidebar">
            <a href="/admin" class="eafd-admin-brand">
                <span>EAFD Admin</span>
            </a>
            <nav>
                <ul class="eafd-admin-menu">
                    <li><a href="/admin">داشبورد</a></li>
                    <li><a href="/admin/design-studio">استودیو طراحی</a></li>
                    <li><a href="/admin/pages">صفحات و صفحه‌ساز</a></li>
                    <li><a href="/admin/services">خدمات</a></li>
                    <li><a href="/admin/projects">پروژه‌ها</a></li>
                    <li><a href="/admin/products">محصولات و لایسنس</a></li>
                    <li><a href="/admin/licenses">مدیریت لایسنس‌ها</a></li>
                    <li><a href="/admin/tools">ابزارها</a></li>
                    <li><a href="/admin/quizzes">آزمون‌ها</a></li>
                    <li><a href="/admin/users">کاربران</a></li>
                    <li><a href="/admin/logs">لاگ‌های سیستم</a></li>
                    <li style="margin-top: 2rem; border-top: 1px solid var(--eafd-color-border, rgba(255,255,255,0.08)); padding-top: 1rem;">
                        <a href="/" target="_blank">مشاهده وب‌سایت ↗</a>
                    </li>
                    <li><a href="/logout">خروج</a></li>
                </ul>
            </nav>
        </aside>

        <main class="eafd-admin-content">
            <?= $content ?>
        </main>
    </div>
</body>
</html>
