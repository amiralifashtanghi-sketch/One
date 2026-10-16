<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'کارت ویزیت آنلاین') ?></title>
    <link rel="stylesheet" href="/assets/fonts/vazirmatn.css">
    <style>
        body { font-family: 'Vazirmatn', system-ui, sans-serif; background: #f8fafc; color: #0f172a; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; padding: 1rem; }
        .card-box { background: #ffffff; padding: 2.5rem 2rem; border-radius: 20px; text-align: center; max-width: 420px; width: 100%; box-shadow: 0 10px 25px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; }
        .icon { font-size: 4rem; margin-bottom: 1rem; }
        h2 { margin: 0 0 1rem; color: #d97706; font-size: 1.4rem; }
        p { color: #64748b; font-size: 0.95rem; line-height: 1.6; margin-bottom: 1.5rem; }
        .badge { background: #fef3c7; color: #92400e; padding: 0.4rem 1rem; border-radius: 50px; font-weight: bold; font-size: 0.9rem; }
        a.btn { display: inline-block; background: #2563eb; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: bold; margin-top: 1rem; }
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
