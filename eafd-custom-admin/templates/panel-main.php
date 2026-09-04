<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$site_name = get_bloginfo( 'name' );
$user_display = $current_user->display_name ? $current_user->display_name : $current_user->user_login;
$initial = mb_substr( $user_display, 0, 1, 'UTF-8' );

// Filter menus for panel sidebar
$all_registered = EAFD_Custom_Admin_Access_Control::get_all_registered_menus();
$panel_menus = array();

if ( $allowed_menus === 'all' ) {
    $panel_menus = $all_registered;
} else {
    foreach ( $all_registered as $item ) {
        if ( in_array( $item['slug'], $allowed_menus, true ) ) {
            $filtered_subs = array();
            if ( ! empty( $item['submenus'] ) ) {
                foreach ( $item['submenus'] as $sub ) {
                    $sub_key = $item['slug'] . '::' . $sub['slug'];
                    if ( in_array( $sub['slug'], $allowed_menus, true ) || in_array( $sub_key, $allowed_menus, true ) ) {
                        $filtered_subs[] = $sub;
                    }
                }
            }
            $item['submenus'] = $filtered_subs;
            $panel_menus[] = $item;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>پنل مدیریت | <?php echo esc_html( $site_name ); ?></title>
    <link rel="stylesheet" href="<?php echo esc_url( EAFD_CUSTOM_ADMIN_URL . 'assets/css/vazirmatn.css' ); ?>">
    <link rel="stylesheet" href="<?php echo esc_url( EAFD_CUSTOM_ADMIN_URL . 'assets/css/panel-base.css' ); ?>">
    <style>
        /* ===== GLASSMORPHISM ADMIN BAR ===== */
        .admin-bar {
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            height: 56px;
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.6);
            display: flex;
            align-items: center;
            padding: 0 16px;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            justify-content: space-between;
        }

        .admin-bar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            color: #1a202c;
            font-size: 16px;
        }

        .brand-logo {
            width: 32px;
            height: 32px;
            background: linear-gradient(145deg, #3a8bc8, #2271b1);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 15px;
            box-shadow: 0 4px 10px rgba(34,113,177,0.3);
        }

        .admin-bar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #2271b1;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(34,113,177,0.25);
        }

        .btn-menu-toggle {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            padding: 8px 14px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            font-family: inherit;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        /* SIDEBAR DRAWER */
        .sidebar-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            z-index: 1050;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .sidebar {
            position: fixed;
            top: 0;
            right: -280px;
            width: 280px;
            height: 100%;
            background: #ffffff;
            z-index: 1100;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: -5px 0 25px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .sidebar.active {
            right: 0;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-menu {
            list-style: none;
            padding: 15px;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 6px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            border-radius: 12px;
            color: #334155;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: #f1f5f9;
            color: #2271b1;
        }

        .sidebar-submenu {
            list-style: none;
            padding-right: 20px;
            margin-top: 4px;
        }

        .sidebar-submenu a {
            font-size: 13px;
            padding: 8px 12px;
            color: #64748b;
        }

        /* BOTTOM NAVIGATION BAR */
        .bottom-nav {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            height: 62px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            border-top: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-around;
            z-index: 1000;
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #64748b;
            text-decoration: none;
            font-size: 11px;
            font-weight: 600;
            gap: 2px;
            flex: 1;
            height: 100%;
        }

        .nav-item.active, .nav-item:hover {
            color: #2271b1;
        }

        .nav-icon {
            font-size: 18px;
        }

        /* MAIN CONTENT AREA & IFRAME */
        .main-content {
            padding: 16px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .iframe-wrapper {
            width: 100%;
            height: calc(100vh - 150px);
            border: none;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 10px 25px rgba(0,0,0,0.04);
            overflow: hidden;
            display: none;
        }

        .iframe-wrapper iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* DASHBOARD CARDS */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
            margin-top: 15px;
        }

        .card {
            background: #ffffff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            border: 1px solid #f1f5f9;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .glance-stats {
            display: flex;
            gap: 12px;
            justify-content: space-between;
        }

        .stat-box {
            flex: 1;
            background: #f8fafc;
            border-radius: 12px;
            padding: 15px;
            text-align: center;
        }

        .stat-number {
            font-size: 22px;
            font-weight: 800;
            color: #2271b1;
        }

        .stat-label {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }
    </style>
</head>
<body class="eafd-admin-panel">

    <!-- ADMIN BAR -->
    <div class="admin-bar">
        <div class="admin-bar-brand">
            <div class="brand-logo">W</div>
            <span><?php echo esc_html( $site_name ); ?></span>
        </div>

        <div class="admin-bar-actions">
            <button class="btn-menu-toggle" onclick="toggleSidebar()">
                <span>☰</span>
                <span>منو</span>
            </button>
            <div class="user-avatar" title="<?php echo esc_attr( $user_display ); ?>">
                <?php echo esc_html( $initial ); ?>
            </div>
        </div>
    </div>

    <!-- SIDEBAR OVERLAY & DRAWER -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    <aside class="sidebar" id="sidebarDrawer">
        <div class="sidebar-header">
            <span style="font-weight: 700; font-size: 16px;">منوی مدیریت</span>
            <button onclick="toggleSidebar()" style="background:none; border:none; font-size: 18px; cursor:pointer;">✕</button>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="#" onclick="showDashboard(); toggleSidebar();" class="active">
                    <span>📊 پیشخوان</span>
                </a>
            </li>
            <?php foreach ( $panel_menus as $menu_item ) : ?>
                <?php
                $admin_url = admin_url( $menu_item['slug'] );
                if ( strpos( $menu_item['slug'], '.php' ) === false ) {
                    $admin_url = admin_url( 'admin.php?page=' . $menu_item['slug'] );
                }
                ?>
                <li>
                    <a href="#" onclick="loadAdminPage('<?php echo esc_js( $admin_url ); ?>', '<?php echo esc_js( $menu_item['title'] ); ?>'); toggleSidebar();">
                        <span><?php echo esc_html( $menu_item['title'] ); ?></span>
                    </a>
                    <?php if ( ! empty( $menu_item['submenus'] ) ) : ?>
                        <ul class="sidebar-submenu">
                            <?php foreach ( $menu_item['submenus'] as $sub ) : ?>
                                <?php
                                $sub_url = admin_url( $sub['slug'] );
                                if ( strpos( $sub['slug'], '.php' ) === false ) {
                                    $sub_url = admin_url( 'admin.php?page=' . $sub['slug'] );
                                }
                                ?>
                                <li>
                                    <a href="#" onclick="loadAdminPage('<?php echo esc_js( $sub_url ); ?>', '<?php echo esc_js( $sub['title'] ); ?>'); toggleSidebar();">
                                        <span>• <?php echo esc_html( $sub['title'] ); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
            <li style="margin-top: 15px; border-top: 1px solid #f1f5f9; padding-top: 10px;">
                <a href="<?php echo esc_url( wp_logout_url( home_url( '/admin' ) ) ); ?>" style="color: #ef4444;">
                    <span>🚪 خروج از حساب</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div id="dashboardView">
            <div style="margin-bottom: 15px;">
                <h1 style="font-size: 22px; font-weight: 800; color: #1e293b;">سلام، <?php echo esc_html( $user_display ); ?> 👋</h1>
                <p style="font-size: 13px; color: #64748b;">به پنل مدیریت خوش آمدید.</p>
            </div>

            <div class="dashboard-grid">
                <!-- Glance Card -->
                <div class="card">
                    <div class="card-title">👁️ در یک نگاه</div>
                    <div class="glance-stats">
                        <div class="stat-box">
                            <div class="stat-number"><?php echo esc_html( wp_count_posts( 'post' )->publish ); ?></div>
                            <div class="stat-label">نوشته‌ها</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-number"><?php echo esc_html( wp_count_posts( 'page' )->publish ); ?></div>
                            <div class="stat-label">برگه‌ها</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-number"><?php echo esc_html( wp_count_comments()->approved ); ?></div>
                            <div class="stat-label">دیدگاه‌ها</div>
                        </div>
                    </div>
                </div>

                <!-- WooCommerce Card if active -->
                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                    <div class="card">
                        <div class="card-title">🛍️ وضعیت فروشگاه</div>
                        <div class="glance-stats">
                            <div class="stat-box">
                                <div class="stat-number"><?php echo esc_html( wp_count_posts( 'product' )->publish ?? 0 ); ?></div>
                                <div class="stat-label">محصولات</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-number"><?php echo esc_html( wc_orders_count( 'processing' ) ?? 0 ); ?></div>
                                <div class="stat-label">سفارشات در حال پردازش</div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- IFRAME CONTAINER FOR WORKING PAGES -->
        <div class="iframe-wrapper" id="iframeWrapper">
            <iframe id="adminIframe" src="about:blank"></iframe>
        </div>
    </main>

    <!-- BOTTOM NAVIGATION BAR -->
    <div class="bottom-nav">
        <a href="#" class="nav-item active" onclick="showDashboard(); return false;">
            <span class="nav-icon">📊</span>
            <span>پیشخوان</span>
        </a>
        <a href="#" class="nav-item" onclick="toggleSidebar(); return false;">
            <span class="nav-icon">📁</span>
            <span>منو</span>
        </a>
        <a href="<?php echo esc_url( home_url() ); ?>" target="_blank" class="nav-item">
            <span class="nav-icon">🌐</span>
            <span>مشاهده سایت</span>
        </a>
        <a href="<?php echo esc_url( wp_logout_url( home_url( '/admin' ) ) ); ?>" class="nav-item" style="color: #ef4444;">
            <span class="nav-icon">🚪</span>
            <span>خروج</span>
        </a>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebarDrawer').classList.toggle('active');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        }

        function showDashboard() {
            document.getElementById('dashboardView').style.display = 'block';
            document.getElementById('iframeWrapper').style.display = 'none';
        }

        function loadAdminPage(url, title) {
            var sep = url.indexOf('?') !== -1 ? '&' : '?';
            var iframeUrl = url + sep + 'eafd_iframe=1';

            document.getElementById('dashboardView').style.display = 'none';
            var wrapper = document.getElementById('iframeWrapper');
            var iframe = document.getElementById('adminIframe');
            wrapper.style.display = 'block';
            iframe.src = iframeUrl;
        }
    </script>
</body>
</html>
