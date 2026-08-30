<?php
if (file_exists(__DIR__ . '/installed.lock')) {
    die("سیستم EAFD قبلاً با موفقیت نصب شده است. جهت امنیت بیشتر مسیر نصب قفل شده است.");
}

function checkServerRequirements(): array {
    $requirements = [
        'php' => [
            'name' => 'نسخه PHP 8.2 یا بالاتر',
            'status' => version_compare(PHP_VERSION, '8.2.0', '>='),
            'current' => PHP_VERSION,
        ],
        'pdo' => [
            'name' => 'افزونه PDO MySQL',
            'status' => extension_loaded('pdo') && extension_loaded('pdo_mysql'),
            'current' => extension_loaded('pdo_mysql') ? 'فعال' : 'غیرفعال',
        ],
        'mbstring' => [
            'name' => 'افزونه MBString',
            'status' => extension_loaded('mbstring'),
            'current' => extension_loaded('mbstring') ? 'فعال' : 'غیرفعال',
        ],
        'config_writable' => [
            'name' => 'دسترسی نوشتن فایل تنظیمات (config/app.php)',
            'status' => is_writable(__DIR__ . '/../config/app.php'),
            'current' => is_writable(__DIR__ . '/../config/app.php') ? 'قابل نوشتن' : 'غیرقابل نوشتن',
        ],
    ];

    $allPassed = true;
    foreach ($requirements as $req) {
        if (!$req['status']) {
            $allPassed = false;
            break;
        }
    }

    return ['items' => $requirements, 'passed' => $allPassed];
}

$reqs = checkServerRequirements();
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>نصب‌کننده اختصاصی EAFD — مرحله ۱</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body style="background: #07090e; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 2rem;">
    <div style="max-width: 650px; width: 100%; background: var(--color-surface); border: 1px solid var(--color-surface-border); border-radius: var(--radius-xl); padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <h1 style="font-size: 1.75rem; color: var(--color-text);">راهنمای نصب اختصاصی EAFD</h1>
            <p style="font-size: 0.9rem; color: var(--color-text-muted);">مرحله ۱ از ۵: بررسی پیش‌نیازها و سازگاری سرور</p>
        </div>

        <div style="background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 2rem;">
            <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.75rem;">
                <?php foreach ($reqs['items'] as $item): ?>
                    <li style="display: flex; justify-content: space-between; align-items: center; font-size: 0.9rem;">
                        <span><?= $item['name'] ?></span>
                        <span style="font-weight: bold; color: <?= $item['status'] ? 'var(--color-success)' : 'var(--color-danger)' ?>;">
                            <?= $item['current'] ?> <?= $item['status'] ? '✓' : '✗' ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <?php if ($reqs['passed']): ?>
            <a href="/install/setup-db.php" class="btn btn-primary" style="width: 100%; justify-content: center;">تایید و ادامه به مرحله پیکربندی دیتابیس ←</a>
        <?php endif; ?>
    </div>
</body>
</html>
