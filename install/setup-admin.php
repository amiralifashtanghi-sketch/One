<?php
session_start();
if (file_exists(__DIR__ . '/installed.lock')) {
    die("سیستم EAFD قبلاً نصب شده است.");
}

$dbConfig = $_SESSION['install_db'] ?? null;
if (!$dbConfig) {
    header('Location: /install/setup-db.php');
    exit;
}

$error = '';
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($requestMethod === 'POST') {
    $username = trim($_POST['admin_user'] ?? '');
    $email = trim($_POST['admin_email'] ?? '');
    $displayName = trim($_POST['admin_name'] ?? 'مدیر سیستم EAFD');
    $password = $_POST['admin_pass'] ?? '';

    if (empty($username) || empty($email) || empty($password)) {
        $error = 'لطفاً تمامی فیلدهای الزامی را تکمیل نمایید.';
    } elseif (strlen($password) < 8) {
        $error = 'رمز عبور باید حداقل ۸ کاراکتر باشد.';
    } else {
        try {
            $dsn = sprintf("mysql:host=%s;dbname=%s;charset=utf8mb4", $dbConfig['host'], $dbConfig['name']);
            $pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            $hashedPass = password_hash($password, PASSWORD_ARGON2ID);
            $stmt = $pdo->prepare("INSERT INTO `eafd_users` (username, password, email, display_name, role) VALUES (:user, :pass, :email, :name, 'admin')");
            $stmt->execute([
                'user' => $username,
                'pass' => $hashedPass,
                'email' => $email,
                'name' => $displayName
            ]);

            $defaultData = require __DIR__ . '/default_data.php';

            $stmtService = $pdo->prepare("INSERT INTO `eafd_services` (title, slug, short_description, full_description, icon_svg, sort_order) VALUES (:title, :slug, :short, :full, :icon, :order)");
            foreach ($defaultData['services'] as $srv) {
                $stmtService->execute([
                    'title' => $srv['title'],
                    'slug' => $srv['slug'],
                    'short' => $srv['short_description'],
                    'full' => $srv['full_description'],
                    'icon' => $srv['icon_svg'],
                    'order' => $srv['sort_order']
                ]);
            }

            $stmtFaq = $pdo->prepare("INSERT INTO `eafd_faqs` (question, answer, sort_order) VALUES (:q, :a, :order)");
            foreach ($defaultData['faqs'] as $index => $faq) {
                $stmtFaq->execute(['q' => $faq['question'], 'a' => $faq['answer'], 'order' => $index + 1]);
            }

            $stmtSetting = $pdo->prepare("INSERT INTO `eafd_settings` (setting_key, setting_value) VALUES (:k, :v)");
            foreach ($defaultData['settings'] as $k => $v) {
                $stmtSetting->execute(['k' => $k, 'v' => $v]);
            }

            $configContent = "<?php\nreturn " . var_export([
                'app' => [
                    'name' => 'EAFD — Digital Engineering System',
                    'url' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost'),
                    'env' => 'production',
                    'timezone' => 'Asia/Tehran',
                    'charset' => 'UTF-8',
                    'lang' => 'fa',
                    'dir' => 'rtl',
                    'version' => '1.0.0',
                ],
                'db' => $dbConfig,
                'security' => [
                    'session_name' => 'EAFD_SESSID',
                    'session_lifetime' => 7200,
                    'hash_algo' => PASSWORD_ARGON2ID,
                ]
            ], true) . ";\n";
            file_put_contents(__DIR__ . '/../config/app.php', $configContent);

            file_put_contents(__DIR__ . '/installed.lock', 'Installed on ' . date('Y-m-d H:i:s'));

            header('Location: /install/complete.php');
            exit;
        } catch (Exception $e) {
            $error = 'خطا در ثبت نهایی اطلاعات: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>نصب‌کننده اختصاصی EAFD — مرحله ۳: ایجاد مدیر و درج داده‌ها</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body style="background: #07090e; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 2rem;">
    <div style="max-width: 600px; width: 100%; background: var(--color-surface); border: 1px solid var(--color-surface-border); border-radius: var(--radius-xl); padding: 2.5rem;">
        <h1 style="font-size: 1.5rem; color: var(--color-text); margin-bottom: 0.5rem; text-align: center;">مرحله ۳: اطلاعات مدیر کل سیستم</h1>
        <p style="font-size: 0.875rem; color: var(--color-text-muted); margin-bottom: 2rem; text-align: center;">حساب کاربری مدیریت و تنظیمات اولیه EAFD را پیکربندی نمایید.</p>

        <?php if ($error): ?>
            <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid var(--color-danger); border-radius: var(--radius-md); padding: 1rem; color: var(--color-danger); font-size: 0.875rem; margin-bottom: 1.5rem;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/install/setup-admin.php" style="display: flex; flex-direction: column; gap: 1.25rem;">
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">نام نمایشی مدیر:</label>
                <input type="text" name="admin_name" value="مدیر سیستم EAFD" required style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">نام کاربری ورود (Username):</label>
                <input type="text" name="admin_user" value="admin" required style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">ایمیل مدیر:</label>
                <input type="email" name="admin_email" placeholder="admin@eafd.ir" required style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>
            <div>
                <label style="display: block; font-size: 0.875rem; color: var(--color-text); margin-bottom: 0.5rem;">رمز عبور مدیر (حداقل ۸ کاراکتر):</label>
                <input type="password" name="admin_pass" required style="width: 100%; padding: 0.75rem; background: var(--color-bg); border: 1px solid var(--color-surface-border); border-radius: var(--radius-md); color: var(--color-text);">
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 1rem; width: 100%;">تکمیل نصب و راه‌اندازی EAFD ←</button>
        </form>
    </div>
</body>
</html>
