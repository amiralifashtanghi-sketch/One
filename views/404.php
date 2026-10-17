<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'صفحه پیدا نشد') ?></title>
    <style>
        body { font-family: Tahoma, Arial, sans-serif; background: #0f172a; color: #f8fafc; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; text-align: center; }
        .card { background: #1e293b; padding: 2rem; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); max-width: 400px; width: 90%; }
        h1 { font-size: 4rem; margin: 0 0 1rem; color: #f43f5e; }
        p { color: #94a3b8; font-size: 1.1rem; margin-bottom: 1.5rem; }
        a { display: inline-block; background: #3b82f6; color: white; padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 8px; font-weight: bold; }
        a:hover { background: #2563eb; }
    </style>
</head>
<body>
    <div class="card">
        <h1>۴۰۴</h1>
        <p>صفحه مورد نظر پیدا نشد یا حذف شده است.</p>
        <a href="/">بازگشت به صفحه اصلی</a>
    </div>
</body>
</html>
