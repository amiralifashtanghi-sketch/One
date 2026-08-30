<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $baseUrl = \App\Core\Security::getBaseUrl(); ?>
    <title><?= \App\Core\Security::escape($pageTitle) ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/style.css">
</head>
<body style="background: #07090e; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 2rem;">
    <div style="max-width: 420px; width: 100%; background: var(--color-surface); border: 1px solid var(--color-surface-border); border-radius: var(--radius-xl); padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <svg width="48" height="48" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 1rem;">
                <rect width="36" height="36" rx="8" fill="#0E121A" stroke="#00F0FF" stroke-width="1.5"/>
                <path d="M10 12H26M10 18H22M10 24H26" stroke="#00F0FF" stroke-width="2.5" stroke-linecap="round"/>
                <circle cx="26" cy="18" r="2.5" fill="#FF8A00"/>
            </svg>
            <h1 style="font-size: 1.5rem; color: var(--color-text);">ورود به پنل مدیریت EAFD</h1>
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-top: 0.25rem;">مرکز مدیریت و کنترل سیستم دیجیتال</p>
        </div>

        <?php if (!empty($error)): ?>
            <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid var(--color-danger); border-radius: var(--radius-md); padding: 0.875rem; color: var(--color-danger); font-size: 0.85rem; margin-bottom: 1.5rem; text-align: center;">
                <?= \App\Core\Security::escape($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= $baseUrl ?>/admin/login" style="display: flex; flex-direction: column; gap: 1.25rem;">
            <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::escape($csrfToken) ?>">

            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--color-text); margin-bottom: 0.5rem;">نام کاربری:</label>
                <input type="text" name="username" required autocomplete="username" style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; color: var(--color-text); margin-bottom: 0.5rem;">رمز عبور:</label>
                <input type="password" name="password" required autocomplete="current-password" style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 0.5rem; width: 100%;">ورود به سامانه ←</button>
        </form>
    </div>
</body>
</html>
