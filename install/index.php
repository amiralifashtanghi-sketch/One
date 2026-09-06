<?php

require_once __DIR__ . '/../app/Core/Autoloader.php';
\App\Core\Autoloader::register(__DIR__ . '/..');
\App\Core\Config::load(__DIR__ . '/../config');

use Install\Installer;

if (Installer::isInstalled()) {
    \App\Core\ErrorHandler::renderErrorPage(403, "سامانه قبلاً نصب شده است", "جهت نصب مجدد، فایل config/installed.lock را حذف نمایید.");
    exit;
}

$step = (int)($_GET['step'] ?? 1);

if ($step === 3 && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbConfig = [
        'driver' => $_POST['driver'] ?? 'sqlite',
        'host' => $_POST['host'] ?? 'localhost',
        'port' => '3306',
        'dbname' => $_POST['dbname'] ?? 'eafd_db',
        'username' => $_POST['username'] ?? 'root',
        'password' => $_POST['password'] ?? '',
        'charset' => 'utf8mb4',
        'sqlite_path' => __DIR__ . '/../storage/database.sqlite',
    ];

    $dbResult = Installer::testDbConnection($dbConfig);
    if ($dbResult['success']) {
        $content = "<?php\n\nreturn " . var_export($dbConfig, true) . ";\n";
        file_put_contents(__DIR__ . '/../config/database.php', $content);
        header('Location: index.php?step=4');
        exit;
    }
}

if ($step === 4 && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $migrateSuccess = Installer::runMigrationsAndSeeds();
    if ($migrateSuccess) {
        header('Location: index.php?step=5');
        exit;
    }
}

if ($step === 5 && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $created = Installer::createAdminAccount(
        $_POST['name'] ?? 'مدیر ارشد EAFD',
        $_POST['email'] ?? 'admin@eafd.ir',
        $_POST['phone'] ?? '09150591710',
        $_POST['password'] ?? 'admin123'
    );
    if ($created) {
        Installer::lockInstallation();
        header('Location: index.php?step=6');
        exit;
    }
}

$checks = ($step === 1) ? Installer::checkEnvironment() : [];
$permissions = ($step === 2) ? Installer::checkPermissions() : [];

?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نصب‌کننده خودکار پلتفرم EAFD</title>
    <link rel="stylesheet" href="/assets/fonts/vazirmatn.css">
    <style>
        :root {
            --bg-color: #090d16;
            --surface-color: #121826;
            --primary-color: #0b63d8;
            --text-color: #f1f5f9;
            --border-color: #1e293b;
            --eafd-font-family: 'Vazirmatn', Tahoma, 'Segoe UI', sans-serif;
        }
        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: var(--eafd-font-family);
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            direction: rtl;
        }
        .installer-card {
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 40px;
            max-width: 650px;
            width: 100%;
            box-shadow: 0 20px 40px rgba(0,0,0,0.6);
        }
        .header {
            text-align: center;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: var(--primary-color);
            margin: 0 0 10px 0;
            font-size: 1.8rem;
        }
        .install-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .install-table th, .install-table td {
            padding: 12px;
            border: 1px solid var(--border-color);
            text-align: right;
        }
        .install-table th {
            background: #182232;
        }
        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        .badge-success { background: #166534; color: #4ade80; }
        .badge-danger { background: #991b1b; color: #fca5a5; }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .form-control {
            width: 100%;
            padding: 12px;
            background: #0d131f;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: #fff;
            font-size: 1rem;
            box-sizing: border-box;
            font-family: inherit;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            border: none;
            font-size: 1rem;
            font-family: inherit;
        }
        .btn-primary { background: var(--primary-color); color: #fff; }
        .btn-secondary { background: #334155; color: #fff; }
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .alert-success { background: #14532d; color: #86efac; }
        .alert-danger { background: #7f1d1d; color: #fca5a5; }
    </style>
</head>
<body>
    <div class="installer-card">
        <div class="header">
            <h1>سامانه نصب خودکار EAFD</h1>
            <p style="color:#94a3b8; margin:0;">راه اندازی سریع پلتفرم وب اختصاصی با PHP و MySQL</p>
        </div>

        <?php
            $viewPath = __DIR__ . "/views/step{$step}.php";
            if (file_exists($viewPath)) {
                require $viewPath;
            } else {
                echo "<p>گام مورد نظر پیدا نشد.</p>";
            }
        ?>
    </div>
</body>
</html>
