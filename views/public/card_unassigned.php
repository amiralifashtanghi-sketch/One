<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'کارت ویزیت آنلاین') ?></title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; background: #0f172a; color: #f8fafc; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; padding: 1rem; }
        .card-box { background: #1e293b; padding: 2.5rem 2rem; border-radius: 20px; text-align: center; max-width: 420px; width: 100%; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.5); border: 1px solid #334155; }
        .icon { font-size: 4rem; margin-bottom: 1rem; }
        h2 { margin: 0 0 1rem; color: #f59e0b; font-size: 1.4rem; }
        p { color: #94a3b8; font-size: 0.95rem; line-height: 1.6; margin-bottom: 1.5rem; }
        .badge { background: rgba(245, 158, 11, 0.2); color: #f59e0b; padding: 0.4rem 1rem; border-radius: 50px; font-weight: bold; font-size: 0.9rem; }
        a.btn { display: inline-block; background: #3b82f6; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: bold; margin-top: 1rem; }
    </style>
</head>
<body>
    <div class="card-box">
        <div class="icon">🎴</div>
        <h2>کارت ویزیت آماده فعال‌سازی</h2>
        <p>کد کارت: <code><?= htmlspecialchars($card['card_code']) ?></code></p>
        <p>این کارت استند/ویزیت آنلاین هنوز به متقاضی واگذار نشده است. جهت خرید یا فعال‌سازی، کد کارت را به فروشنده تحویل دهید.</p>
        <div>
            <span class="badge">وضعیت: بدون مالک</span>
        </div>
        <a href="/login" class="btn">ورود فروشنده / مدیر</a>
    </div>
</body>
</html>
