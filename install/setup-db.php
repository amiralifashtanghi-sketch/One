<?php
if (file_exists(__DIR__ . '/installed.lock')) {
    die("سیستم EAFD قبلاً نصب شده است.");
}

$error = '';
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($requestMethod === 'POST') {
    $dbHost = trim($_POST['db_host'] ?? 'localhost');
    $dbName = trim($_POST['db_name'] ?? '');
    $dbUser = trim($_POST['db_user'] ?? '');
    $dbPass = trim($_POST['db_pass'] ?? '');

    if (empty($dbName) || empty($dbUser)) {
        $error = 'لطفاً نام دیتابیس و نام کاربری را وارد کنید.';
    } else {
        try {
            $dsn = sprintf("mysql:host=%s;dbname=%s;charset=utf8mb4", $dbHost, $dbName);
            $pdo = new PDO($dsn, $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 5
            ]);

            session_start();
            $_SESSION['install_db'] = [
                'host' => $dbHost,
                'name' => $dbName,
                'user' => $dbUser,
                'pass' => $dbPass
            ];

            $schemaSql = require __DIR__ . '/schema.php';
            $pdo->exec($schemaSql);

            header('Location: setup-admin.php');
            exit;
        } catch (PDOException $e) {
            $error = 'خطا در اتصال به دیتابیس یا ساخت جداول: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>نصب‌کننده اختصاصی EAFD — مرحله ۲: اتصال به دیتابیس</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="background: #07090e; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 2rem;">
    <div style="max-width: 600px; width: 100%; background: var(--color-surface); border: 1px solid var(--color-surface-border); border-radius: var(--radius-xl); padding: 2.5rem;">
        <h1 style="font-size: 1.5rem; color: var(--color-text); margin-bottom: 0.5rem; text-align: center;">مرحله ۲: اطلاعات اتصال دیتابیس</h1>
        <p style="font-size: 0.875rem; color: var(--color-text-muted); margin-bottom: 2rem; text-align: center;">اطلاعات پایگاه داده MySQL/MariaDB خود را وارد نمایید.</p>

        <?php if ($error): ?>
            <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid var(--color-danger); border-radius: var(--radius-md); padding: 1rem; color: var(--color-danger); font-size: 0.875rem; margin-bottom: 1.5rem;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="setup-db.php" style="display: flex; flex-direction: column; gap: 1.25rem;">
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">آدرس سرور دیتابیس (Host):</label>
                <input type="text" name="db_host" value="localhost" required style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">نام دیتابیس (Database Name):</label>
                <input type="text" name="db_name" placeholder="eafd_db" required style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">نام کاربری دیتابیس (Database User):</label>
                <input type="text" name="db_user" placeholder="root" required style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">رمز عبور دیتابیس (Database Password):</label>
                <input type="password" name="db_pass" placeholder="••••••••" style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 1rem; width: 100%;">بررسی اتصال و ایجاد ساختار جداول ←</button>
        </form>
    </div>
</body>
</html>
