<?php

session_start();

$lockFile = __DIR__ . '/../config/installed.lock';
if (file_exists($lockFile)) {
    die("<!DOCTYPE html><html lang='fa' dir='rtl'><head><meta charset='UTF-8'><title>نصب شده</title><style>body{font-family:Tahoma,sans-serif;background:#0f172a;color:#fff;display:flex;justify-content:center;align-items:center;height:100vh;margin:0;}.box{background:#1e293b;padding:2rem;border-radius:12px;text-align:center;}a{color:#3b82f6;text-decoration:none;font-weight:bold;}</style></head><body><div class='box'><h2>سیستم بیو لینک قبلاً نصب شده است!</h2><p>جهت نصب مجدد، لطفاً فایل <code>config/installed.lock</code> را حذف کنید.</p><a href='/login'>ورود به پنل کاربری</a></div></body></html>");
}

$step = (int)($_GET['step'] ?? 1);
$error = '';
$success = '';

// Step 1: Requirements Check
$phpVersionValid = version_compare(PHP_VERSION, '8.2.0', '>=');
$pdoLoaded = extension_loaded('pdo') && extension_loaded('pdo_mysql');
$curlLoaded = extension_loaded('curl');
$gdLoaded = extension_loaded('gd');
$configWritable = is_writable(__DIR__ . '/../config');
$uploadsWritable = is_writable(__DIR__ . '/../uploads') || @mkdir(__DIR__ . '/../uploads', 0777, true);

$allRequirementsPassed = $phpVersionValid && $pdoLoaded && $curlLoaded && $gdLoaded && $configWritable && $uploadsWritable;

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step === 2) {
        // Test & Save DB config
        $host = trim($_POST['db_host'] ?? 'localhost');
        $port = trim($_POST['db_port'] ?? '3306');
        $dbname = trim($_POST['db_name'] ?? '');
        $user = trim($_POST['db_user'] ?? '');
        $pass = trim($_POST['db_pass'] ?? '');

        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            $_SESSION['install_db'] = [
                'host' => $host,
                'port' => $port,
                'dbname' => $dbname,
                'username' => $user,
                'password' => $pass,
                'charset' => 'utf8mb4'
            ];

            header("Location: index.php?step=3");
            exit;
        } catch (\Exception $e) {
            $error = 'خطا در اتصال به پایگاه داده: ' . $e->getMessage();
        }
    } elseif ($step === 3) {
        // Admin & SMS Settings
        $adminPhone = trim($_POST['admin_phone'] ?? '');
        $adminPass = trim($_POST['admin_pass'] ?? '');
        $adminName = trim($_POST['admin_name'] ?? 'مدیر کل');
        $smsApiKey = trim($_POST['sms_api_key'] ?? '');
        $smsTemplateId = trim($_POST['sms_template_id'] ?? '');
        $smsParamName = trim($_POST['sms_param_name'] ?? 'CODE');

        if (empty($adminPhone) || empty($adminPass)) {
            $error = 'لطفاً شماره موبایل و رمز عبور مدیر کل را وارد کنید.';
        } else {
            $_SESSION['install_admin'] = [
                'phone' => $adminPhone,
                'password' => $adminPass,
                'name' => $adminName,
                'sms_api_key' => $smsApiKey,
                'sms_template_id' => $smsTemplateId,
                'sms_param_name' => $smsParamName
            ];

            header("Location: index.php?step=4");
            exit;
        }
    } elseif ($step === 4) {
        // Run Migration
        $dbConfig = $_SESSION['install_db'] ?? null;
        $adminConfig = $_SESSION['install_admin'] ?? null;

        if (!$dbConfig || !$adminConfig) {
            $error = 'اطلاعات مراحل قبلی یافت نشد. لطفاً از گام ۱ شروع کنید.';
        } else {
            try {
                $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['dbname']};charset=utf8mb4";
                $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);

                // Run SQL Schema
                $sql = file_get_contents(__DIR__ . '/schema.sql');
                $pdo->exec($sql);

                // Insert Admin User
                $persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
                $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
                $normPhone = str_replace($persianDigits, $englishDigits, $adminConfig['phone']);
                $normPhone = preg_replace('/[^0-9]/', '', $normPhone);
                if (strlen($normPhone) === 10 && strpos($normPhone, '9') === 0) {
                    $normPhone = '0' . $normPhone;
                }

                $hashedPass = password_hash($adminConfig['password'], PASSWORD_BCRYPT, ['cost' => 12]);

                $stmt = $pdo->prepare("INSERT INTO users (phone, password, name, role) VALUES (?, ?, ?, 'admin') ON DUPLICATE KEY UPDATE password = VALUES(password)");
                $stmt->execute([$normPhone, $hashedPass, $adminConfig['name']]);

                // Insert Settings
                $settings = [
                    'sms_api_key' => $adminConfig['sms_api_key'],
                    'sms_template_id' => $adminConfig['sms_template_id'],
                    'sms_param_name' => $adminConfig['sms_param_name'] ?? 'CODE',
                    'site_title' => 'سامانه کارت و بیو لینک اختصاصی',
                    'app_url' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST']
                ];

                $stmtSetting = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
                foreach ($settings as $key => $val) {
                    $stmtSetting->execute([$key, $val]);
                }

                // Write db.php config file
                $dbConfigFile = __DIR__ . '/../config/db.php';
                $dbContent = "<?php\n\nreturn " . var_export($dbConfig, true) . ";\n";
                file_put_contents($dbConfigFile, $dbContent);

                // Write lock file
                file_put_contents($lockFile, date('Y-m-d H:i:s'));

                unset($_SESSION['install_db'], $_SESSION['install_admin']);

                $success = 'نصب سامانه با موفقیت به پایان رسید!';
            } catch (\Exception $e) {
                $error = 'خطا در نصب دیتابیس: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نصب‌کننده وب سامانه بیو لینک</title>
    <style>
        :root {
            --bg: #0f172a;
            --card-bg: #1e293b;
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --text: #f8fafc;
            --muted: #94a3b8;
            --success: #10b981;
            --danger: #ef4444;
            --border: #334155;
        }
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
            padding: 2rem 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            box-sizing: border-box;
        }
        .container {
            width: 100%;
            max-width: 600px;
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
            padding: 2rem;
            border: 1px solid var(--border);
        }
        .steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--border);
            padding-bottom: 1rem;
        }
        .step-item {
            font-size: 0.9rem;
            color: var(--muted);
            font-weight: 500;
        }
        .step-item.active {
            color: var(--primary);
            font-weight: bold;
        }
        .step-item.done {
            color: var(--success);
        }
        h2 { margin-top: 0; font-size: 1.5rem; color: #fff; }
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }
        .alert-danger { background: rgba(239, 68, 68, 0.15); border: 1px solid var(--danger); color: #fca5a5; }
        .alert-success { background: rgba(16, 185, 129, 0.15); border: 1px solid var(--success); color: #6ee7b7; }
        .req-list { list-style: none; padding: 0; margin: 0 0 1.5rem 0; }
        .req-list li {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .req-list li:last-child { border-bottom: none; }
        .badge {
            padding: 0.25rem 0.6rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        .badge-success { background: var(--success); color: #fff; }
        .badge-danger { background: var(--danger); color: #fff; }
        .form-group { margin-bottom: 1.25rem; }
        label { display: block; margin-bottom: 0.5rem; color: var(--muted); font-size: 0.9rem; }
        input[type="text"], input[type="password"], input[type="number"] {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #0f172a;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            box-sizing: border-box;
            font-size: 1rem;
            outline: none;
        }
        input:focus { border-color: var(--primary); }
        .btn {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 0.85rem 1.75rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
            width: 100%;
            text-align: center;
            box-sizing: border-box;
        }
        .btn:hover { background: var(--primary-hover); }
        .btn-success { background: var(--success); }
        .btn-success:hover { background: #059669; }
        .hint { font-size: 0.8rem; color: var(--muted); margin-top: 0.25rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="steps">
            <span class="step-item <?= $step === 1 ? 'active' : ($step > 1 ? 'done' : '') ?>">۱. بررسی نیازمندی‌ها</span>
            <span class="step-item <?= $step === 2 ? 'active' : ($step > 2 ? 'done' : '') ?>">۲. اتصال دیتابیس</span>
            <span class="step-item <?= $step === 3 ? 'active' : ($step > 3 ? 'done' : '') ?>">۳. مدیر و پیامک</span>
            <span class="step-item <?= $step === 4 ? 'active' : '' ?>">۴. تکمیل نصب</span>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <p>سامانه با موفقیت راه‌اندازی شد. اکنون می‌توانید وارد پنل مدیریت شوید.</p>
            <a href="/login" class="btn btn-success">ورود به پنل مدیریت</a>
        <?php elseif ($step === 1): ?>
            <h2>گام ۱: بررسی نیازمندی‌های سرور</h2>
            <ul class="req-list">
                <li>
                    <span>نسخه PHP (حداقل 8.2)</span>
                    <span class="badge <?= $phpVersionValid ? 'badge-success' : 'badge-danger' ?>"><?= PHP_VERSION ?></span>
                </li>
                <li>
                    <span>افزونه PDO MySQL</span>
                    <span class="badge <?= $pdoLoaded ? 'badge-success' : 'badge-danger' ?>"><?= $pdoLoaded ? 'فعال' : 'غیرفعال' ?></span>
                </li>
                <li>
                    <span>افزونه cURL</span>
                    <span class="badge <?= $curlLoaded ? 'badge-success' : 'badge-danger' ?>"><?= $curlLoaded ? 'فعال' : 'غیرفعال' ?></span>
                </li>
                <li>
                    <span>افزونه GD (تصاویر)</span>
                    <span class="badge <?= $gdLoaded ? 'badge-success' : 'badge-danger' ?>"><?= $gdLoaded ? 'فعال' : 'غیرفعال' ?></span>
                </li>
                <li>
                    <span>مجوز نوشتن در پوشه config/</span>
                    <span class="badge <?= $configWritable ? 'badge-success' : 'badge-danger' ?>"><?= $configWritable ? 'مجاز' : 'غیرمجاز' ?></span>
                </li>
                <li>
                    <span>مجوز نوشتن در پوشه uploads/</span>
                    <span class="badge <?= $uploadsWritable ? 'badge-success' : 'badge-danger' ?>"><?= $uploadsWritable ? 'مجاز' : 'غیرمجاز' ?></span>
                </li>
            </ul>

            <?php if ($allRequirementsPassed): ?>
                <a href="index.php?step=2" class="btn">ادامه و تنظیمات دیتابیس &larr;</a>
            <?php else: ?>
                <div class="alert alert-danger">لطفاً ابتدا پیش‌نیازهای غیرفعال بالا را در سرور رفع کنید.</div>
            <?php endif; ?>

        <?php elseif ($step === 2): ?>
            <h2>گام ۲: تنظیمات پایگاه داده (MySQL)</h2>
            <form method="POST">
                <div class="form-group">
                    <label>آدرس هاست دیتابیس (Database Host):</label>
                    <input type="text" name="db_host" value="localhost" required>
                </div>
                <div class="form-group">
                    <label>پورت دیتابیس (Database Port):</label>
                    <input type="number" name="db_port" value="3306" required>
                </div>
                <div class="form-group">
                    <label>نام دیتابیس (Database Name):</label>
                    <input type="text" name="db_name" placeholder="مثال: my_bio_db" required>
                </div>
                <div class="form-group">
                    <label>نام کاربری دیتابیس (Database User):</label>
                    <input type="text" name="db_user" placeholder="مثال: root" required>
                </div>
                <div class="form-group">
                    <label>رمز عبور دیتابیس (Database Password):</label>
                    <input type="password" name="db_pass">
                </div>
                <button type="submit" class="btn">تست اتصال و مرحله بعد &larr;</button>
            </form>

        <?php elseif ($step === 3): ?>
            <h2>گام ۳: تنظیمات حساب مدیر کل و سامانه SMS.ir</h2>
            <form method="POST">
                <div class="form-group">
                    <label>نام و نام خانوادگی مدیر کل:</label>
                    <input type="text" name="admin_name" value="مدیر کل" required>
                </div>
                <div class="form-group">
                    <label>شماره همراه مدیر کل (جهت ورود):</label>
                    <input type="text" name="admin_phone" placeholder="مثال: 09121234567" required>
                </div>
                <div class="form-group">
                    <label>رمز عبور حساب مدیر کل:</label>
                    <input type="password" name="admin_pass" required>
                </div>
                <hr style="border-color: var(--border); margin: 1.5rem 0;">
                <div class="form-group">
                    <label>کلید API سامانه SMS.ir (API Key):</label>
                    <input type="text" name="sms_api_key" placeholder="کلید API دریافت شده از SMS.ir">
                </div>
                <div class="form-group">
                    <label>شناسه پترن OTP در SMS.ir (Template ID):</label>
                    <input type="text" name="sms_template_id" placeholder="مثال: 100000">
                    <div class="hint">شناسه پترنی که متغیر کد تایید در آن قرار داده شده است.</div>
                </div>
                <div class="form-group">
                    <label>نام متغیر کد در پترن SMS.ir:</label>
                    <input type="text" name="sms_param_name" value="CODE">
                    <div class="hint">مثلاً اگر در پترن نوشته‌اید {CODE} اینجا CODE وارد کنید.</div>
                </div>
                <button type="submit" class="btn">ذخیره و ورود به مرحله نهایی &larr;</button>
            </form>

        <?php elseif ($step === 4): ?>
            <h2>گام ۴: تایید نهایی و راه‌اندازی دیتابیس</h2>
            <p>تمامی اطلاعات ذخیره شده است. با کلیک روی دکمه زیر، جداول دیتابیس ساخته شده و حساب مدیر ایجاد می‌گردد.</p>
            <form method="POST">
                <button type="submit" class="btn btn-success">اجرای نصب و راه‌اندازی سامانه &check;</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
