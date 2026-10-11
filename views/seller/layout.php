<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'پنل فروشنده') ?></title>
    <style>
        :root {
            --bg: #0f172a;
            --card-bg: #1e293b;
            --primary: #10b981;
            --primary-hover: #059669;
            --text: #f8fafc;
            --muted: #94a3b8;
            --border: #334155;
            --danger: #ef4444;
            --success: #10b981;
            --warning: #f59e0b;
        }
        * { box-sizing: border-box; }
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background: #13222e;
            border-left: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }
        .brand {
            padding: 1.5rem;
            font-size: 1.2rem;
            font-weight: bold;
            color: #fff;
            border-bottom: 1px solid var(--border);
            text-align: center;
        }
        .menu {
            list-style: none;
            padding: 1rem 0;
            margin: 0;
            flex: 1;
        }
        .menu li a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1.5rem;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.2s;
        }
        .menu li a:hover, .menu li a.active {
            background: rgba(16, 185, 129, 0.15);
            color: var(--primary);
            border-right: 4px solid var(--primary);
        }
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }
        .header {
            height: 65px;
            background: var(--card-bg);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
        }
        .content {
            padding: 2rem;
            flex: 1;
        }
        .card {
            background: var(--card-bg);
            border-radius: 12px;
            border: 1px solid var(--border);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            padding: 0.85rem 1rem;
            text-align: right;
            border-bottom: 1px solid var(--border);
            font-size: 0.9rem;
        }
        th { color: var(--muted); font-weight: 600; background: rgba(0,0,0,0.2); }
        .btn {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 0.75rem 1.25rem;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn:hover { background: var(--primary-hover); }
        .btn-danger { background: var(--danger); }
        .form-group { margin-bottom: 1.25rem; }
        label { display: block; margin-bottom: 0.5rem; color: var(--muted); font-size: 0.85rem; }
        input {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #0f172a;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            box-sizing: border-box;
            outline: none;
        }
        .alert {
            padding: 0.85rem 1rem;
            border-radius: 8px;
            margin-bottom: 1.25rem;
            font-size: 0.9rem;
        }
        .alert-danger { background: rgba(239, 68, 68, 0.15); border: 1px solid var(--danger); color: #fca5a5; }
        .alert-success { background: rgba(16, 185, 129, 0.15); border: 1px solid var(--success); color: #6ee7b7; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="brand">🛒 پنل اختصاصی فروشنده</div>
        <ul class="menu">
            <li><a href="/seller" class="<?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>">📊 داشبورد فروشنده</a></li>
            <li><a href="/seller/assign" class="<?= ($activePage ?? '') === 'assign' ? 'active' : '' ?>">📱 تخصیص کارت به خریدار</a></li>
            <li><a href="/seller/history" class="<?= ($activePage ?? '') === 'history' ? 'active' : '' ?>">📜 تاریخچه فروش</a></li>
            <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
                <li><a href="/admin" style="color: var(--warning);">👑 بازگشت به مدیریت کل</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <div>فروشنده: <?= htmlspecialchars($_SESSION['user_name'] ?? 'همکار محترم') ?> 👋</div>
            <div>
                <a href="/logout" class="btn btn-danger" style="padding: 0.4rem 0.85rem; font-size: 0.85rem;">خروج</a>
            </div>
        </div>

        <div class="content">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <?= $content ?? '' ?>
        </div>
    </div>
</body>
</html>
