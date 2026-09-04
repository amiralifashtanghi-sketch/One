<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود به پنل مدیریت | <?php echo esc_html( get_bloginfo( 'name' ) ); ?></title>
    <link rel="stylesheet" href="<?php echo esc_url( EAFD_CUSTOM_ADMIN_URL . 'assets/css/vazirmatn.css' ); ?>">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Vazirmatn', sans-serif;
            background: linear-gradient(145deg, #e6e9f0 0%, #f0f2f5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 400px;
            padding: 30px 25px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.06);
            border: 1px solid rgba(255,255,255,0.8);
            text-align: center;
        }
        .login-logo {
            width: 60px;
            height: 60px;
            background: #2271b1;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            font-weight: 800;
            margin: 0 auto 15px;
            box-shadow: 0 6px 15px rgba(34,113,177,0.3);
        }
        .login-title {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 5px;
        }
        .login-subtitle {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 25px;
        }
        .form-group {
            margin-bottom: 18px;
            text-align: right;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }
        .form-input {
            width: 100%;
            padding: 12px 15px;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: all 0.2s ease;
        }
        .form-input:focus {
            border-color: #2271b1;
            box-shadow: 0 0 0 3px rgba(34,113,177,0.15);
        }
        .btn-submit {
            width: 100%;
            padding: 13px;
            border-radius: 12px;
            border: none;
            background: linear-gradient(145deg, #3a8bc8, #2271b1);
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            font-family: inherit;
            cursor: pointer;
            box-shadow: 0 6px 18px rgba(34,113,177,0.3);
            transition: all 0.2s ease;
        }
        .btn-submit:active {
            transform: scale(0.98);
        }
        .alert-box {
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 15px;
            display: none;
        }
        .alert-danger {
            background: #fef2f2;
            color: #ef4444;
            border: 1px solid #fca5a5;
        }
        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #86efac;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-logo">W</div>
        <h1 class="login-title">ورود به پنل مدیریت</h1>
        <p class="login-subtitle"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>

        <div id="alertBox" class="alert-box"></div>

        <form id="eafdLoginForm">
            <div class="form-group">
                <label>شماره موبایل:</label>
                <input type="text" id="phoneInput" class="form-input" placeholder="09123456789" dir="ltr" required>
            </div>

            <div class="form-group">
                <label>رمز ورود:</label>
                <input type="password" id="passwordInput" class="form-input" placeholder="••••••••" dir="ltr" required>
            </div>

            <button type="submit" id="submitBtn" class="btn-submit">ورود به حساب</button>
        </form>
    </div>

    <script>
        document.getElementById('eafdLoginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var phone = document.getElementById('phoneInput').value;
            var password = document.getElementById('passwordInput').value;
            var alertBox = document.getElementById('alertBox');
            var btn = document.getElementById('submitBtn');

            btn.disabled = true;
            btn.innerText = 'در حال بررسی...';
            alertBox.style.display = 'none';

            var formData = new FormData();
            formData.append('action', 'eafd_custom_admin_login');
            formData.append('security', '<?php echo esc_js( wp_create_nonce( 'eafd_login_nonce' ) ); ?>');
            formData.append('phone', phone);
            formData.append('password', password);

            fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerText = 'ورود به حساب';
                if (data.success) {
                    alertBox.className = 'alert-box alert-success';
                    alertBox.innerText = data.data.message;
                    alertBox.style.display = 'block';
                    setTimeout(function() {
                        window.location.href = data.data.redirect_url;
                    }, 800);
                } else {
                    alertBox.className = 'alert-box alert-danger';
                    alertBox.innerText = data.data.message;
                    alertBox.style.display = 'block';
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerText = 'ورود به حساب';
                alertBox.className = 'alert-box alert-danger';
                alertBox.innerText = 'خطا در ارتباط با سرور.';
                alertBox.style.display = 'block';
            });
        });
    </script>
</body>
</html>
