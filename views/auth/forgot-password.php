<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'بازیابی رمز عبور') ?></title>
    <style>
        :root {
            --bg: #0f172a;
            --card-bg: #1e293b;
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --text: #f8fafc;
            --muted: #94a3b8;
            --danger: #ef4444;
            --success: #10b981;
            --border: #334155;
        }
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            box-sizing: border-box;
        }
        .card {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 16px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
            border: 1px solid var(--border);
        }
        .form-group { margin-bottom: 1.25rem; }
        label { display: block; margin-bottom: 0.5rem; color: var(--muted); font-size: 0.85rem; }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #0f172a;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            box-sizing: border-box;
            outline: none;
            font-size: 1rem;
        }
        input:focus { border-color: var(--primary); }
        .btn {
            width: 100%;
            padding: 0.85rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn:hover { background: var(--primary-hover); }
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        .alert-danger { background: rgba(239, 68, 68, 0.15); border: 1px solid var(--danger); color: #fca5a5; }
        .alert-success { background: rgba(16, 185, 129, 0.15); border: 1px solid var(--success); color: #6ee7b7; }
        .otp-row { display: flex; gap: 0.5rem; }
        .btn-otp { width: auto; font-size: 0.85rem; white-space: nowrap; padding: 0.75rem 1rem; }
        .links { margin-top: 1.25rem; text-align: center; font-size: 0.85rem; }
        .links a { color: var(--primary); text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <h2 style="text-align: center; margin-top: 0; margin-bottom: 1.5rem;">بازیابی رمز عبور پیامکی</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="/forgot-password">
            <div class="form-group">
                <label>شماره همراه:</label>
                <div class="otp-row">
                    <input type="text" id="phone" name="phone" placeholder="۰۹۱۲۱۲۳۴۵۶۷" required>
                    <button type="button" class="btn btn-otp" id="sendOtpBtn" onclick="requestOtp()">ارسال کد</button>
                </div>
            </div>
            <div class="form-group">
                <label>کد تایید ۴ رقمی:</label>
                <input type="text" name="otp_code" placeholder="۱۲۳۴" required>
            </div>
            <div class="form-group">
                <label>رمز عبور جدید:</label>
                <input type="password" name="new_password" required>
            </div>
            <button type="submit" class="btn">تغییر رمز عبور و ذخیره</button>
        </form>

        <div class="links">
            <a href="/login">&rarr; بازگشت به صفحه ورود</a>
        </div>
    </div>

    <script>
        function requestOtp() {
            const phone = document.getElementById('phone').value.trim();
            const btn = document.getElementById('sendOtpBtn');
            if (!phone) {
                alert('لطفاً شماره همراه خود را وارد کنید.');
                return;
            }

            btn.disabled = true;
            btn.innerText = '⏳ در حال ارسال...';

            fetch('/api/send-otp', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ phone: phone })
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    let seconds = 120;
                    const timer = setInterval(() => {
                        seconds--;
                        btn.innerText = `ارسال مجدد (${seconds})`;
                        if (seconds <= 0) {
                            clearInterval(timer);
                            btn.disabled = false;
                            btn.innerText = 'ارسال کد';
                        }
                    }, 1000);
                } else {
                    btn.disabled = false;
                    btn.innerText = 'ارسال کد';
                }
            })
            .catch(err => {
                alert('خطا در ارسال درخواست.');
                btn.disabled = false;
                btn.innerText = 'ارسال کد';
            });
        }
    </script>
</body>
</html>
