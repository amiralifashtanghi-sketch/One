<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'ورود به سامانه') ?></title>
    <link rel="stylesheet" href="/assets/fonts/vazirmatn.css">
    <style>
        :root {
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --text: #0f172a;
            --muted: #64748b;
            --danger: #ef4444;
            --success: #10b981;
            --border: #e2e8f0;
        }
        body {
            font-family: 'Vazirmatn', system-ui, sans-serif;
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
        .login-card {
            background: var(--card-bg);
            padding: 2.5rem 2rem;
            border-radius: 16px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            border: 1px solid var(--border);
        }
        .tabs {
            display: flex;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--border);
        }
        .tab-btn {
            flex: 1;
            padding: 0.75rem;
            background: none;
            border: none;
            color: var(--muted);
            font-size: 0.95rem;
            font-weight: bold;
            cursor: pointer;
            border-bottom: 2px solid transparent;
        }
        .tab-btn.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }
        .form-group { margin-bottom: 1.25rem; }
        label { display: block; margin-bottom: 0.5rem; color: var(--muted); font-size: 0.85rem; }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #ffffff;
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
        .alert-danger { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }
        .alert-success { background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; }
        .links { margin-top: 1.25rem; text-align: center; font-size: 0.85rem; }
        .links a { color: var(--primary); text-decoration: none; }
        .otp-row { display: flex; gap: 0.5rem; }
        .btn-otp { width: auto; font-size: 0.85rem; white-space: nowrap; padding: 0.75rem 1rem; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2 style="text-align: center; margin-top: 0; margin-bottom: 1.5rem; color: var(--primary);">ورود به سامانه بیو لینک</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab('pass')">ورود با رمز عبور</button>
            <button class="tab-btn" onclick="switchTab('otp')">ورود با کد پیامکی</button>
        </div>

        <!-- Password Form -->
        <form id="passForm" method="POST" action="/login">
            <div class="form-group">
                <label>شماره همراه:</label>
                <input type="text" name="phone" placeholder="۰۹۱۲۱۲۳۴۵۶۷" required>
            </div>
            <div class="form-group">
                <label>رمز عبور:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">ورود به پنل</button>
        </form>

        <!-- OTP Form -->
        <form id="otpForm" method="POST" action="/login/otp" style="display: none;">
            <div class="form-group">
                <label>شماره همراه:</label>
                <div class="otp-row">
                    <input type="text" id="otpPhone" name="phone" placeholder="۰۹۱۲۱۲۳۴۵۶۷" required>
                    <button type="button" class="btn btn-otp" id="sendOtpBtn" onclick="requestOtp()">ارسال کد</button>
                </div>
            </div>
            <div class="form-group">
                <label>کد ۴ رقمی تایید:</label>
                <input type="text" name="otp_code" placeholder="۱۲۳۴" required>
            </div>
            <button type="submit" class="btn">ورود با کد تایید</button>
        </form>

        <div class="links">
            <a href="/forgot-password">رمز عبور خود را فراموش کرده‌اید؟ بازیابی با پیامک</a>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            if (tab === 'pass') {
                document.querySelectorAll('.tab-btn')[0].classList.add('active');
                document.getElementById('passForm').style.display = 'block';
                document.getElementById('otpForm').style.display = 'none';
            } else {
                document.querySelectorAll('.tab-btn')[1].classList.add('active');
                document.getElementById('passForm').style.display = 'none';
                document.getElementById('otpForm').style.display = 'block';
            }
        }

        function requestOtp() {
            const phone = document.getElementById('otpPhone').value.trim();
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
