<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'پنل فروشنده') ?></title>
    <link rel="stylesheet" href="/assets/fonts/vazirmatn.css">
    <style>
        :root {
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --sidebar-bg: #064e3b;
            --primary: #10b981;
            --primary-hover: #059669;
            --text: #0f172a;
            --muted: #64748b;
            --border: #e2e8f0;
            --danger: #ef4444;
            --success: #10b981;
            --warning: #f59e0b;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Vazirmatn', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }
        .sidebar {
            width: 250px;
            background: var(--sidebar-bg);
            border-left: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            color: #fff;
            transition: transform 0.3s ease;
        }
        .brand {
            padding: 1.5rem;
            font-size: 1.2rem;
            font-weight: bold;
            color: #fff;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        .menu {
            list-style: none;
            padding: 1rem 0;
            margin: 0;
            flex: 1;
            overflow-y: auto;
        }
        .menu li a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1.5rem;
            color: #a7f3d0;
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .menu li a:hover, .menu li a.active {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            border-right: 4px solid #ffffff;
        }
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            width: 100%;
        }
        .header {
            height: 65px;
            background: var(--card-bg);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .hamburger-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text);
            padding: 0.25rem 0.5rem;
        }
        .content {
            padding: 1.5rem;
            flex: 1;
            overflow-x: hidden;
        }
        .card {
            background: var(--card-bg);
            border-radius: 12px;
            border: 1px solid var(--border);
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            max-width: 100%;
            overflow-x: auto;
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
            white-space: nowrap;
        }
        th { color: var(--muted); font-weight: 600; background: #f1f5f9; }
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
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            box-sizing: border-box;
            outline: none;
        }
        input:focus { border-color: var(--primary); }
        .alert {
            padding: 0.85rem 1rem;
            border-radius: 8px;
            margin-bottom: 1.25rem;
            font-size: 0.9rem;
        }
        .alert-danger { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }
        .alert-success { background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                right: -250px;
                height: 100vh;
                z-index: 9999;
            }
            .sidebar.active {
                right: 0;
            }
            .hamburger-btn {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="brand">🛒 پنل اختصاصی فروشنده</div>
        <ul class="menu">
            <li><a href="/seller" class="<?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>">📊 داشبورد فروشنده</a></li>
            <li><a href="/seller/assign" class="<?= ($activePage ?? '') === 'assign' ? 'active' : '' ?>">📱 تخصیص کارت به خریدار</a></li>
            <li><a href="/seller/history" class="<?= ($activePage ?? '') === 'history' ? 'active' : '' ?>">📜 تاریخچه فروش</a></li>
            <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
                <li><a href="/admin" style="color: #fde047;">👑 بازگشت به مدیریت کل</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <button class="hamburger-btn" onclick="toggleSidebar()">☰</button>
                <span>فروشنده: <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'همکار محترم') ?></strong> 👋</span>
            </div>
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

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
    </script>
</body>
</html>
