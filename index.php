<?php

// Check installation
$lockFile = __DIR__ . '/config/installed.lock';
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

if (!file_exists($lockFile)) {
    if (strpos($uri, '/install') === 0) {
        require __DIR__ . '/install/index.php';
        exit;
    }
    header("Location: /install");
    exit;
}

// Autoload
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Config;
use App\Core\Database;
use App\Core\Request;
use App\Core\Router;
use App\Core\Session;

// Start session
Session::start();

// Connect to DB if installed
if (file_exists($lockFile)) {
    $dbConfigFile = __DIR__ . '/config/db.php';
    if (file_exists($dbConfigFile)) {
        $dbConfig = require $dbConfigFile;
        Database::connect($dbConfig);
    }
}

// Register Routes
// 1. Auth
Router::get('/login', [\App\Controllers\AuthController::class, 'loginView']);
Router::post('/login', [\App\Controllers\AuthController::class, 'loginProcess']);
Router::post('/login/otp', [\App\Controllers\AuthController::class, 'loginWithOtpProcess']);
Router::get('/forgot-password', [\App\Controllers\AuthController::class, 'forgotPasswordView']);
Router::post('/forgot-password', [\App\Controllers\AuthController::class, 'resetPasswordProcess']);
Router::get('/logout', [\App\Controllers\AuthController::class, 'logout']);

// 2. Admin Panel
Router::get('/admin', [\App\Controllers\AdminController::class, 'dashboard']);
Router::get('/admin/cards', [\App\Controllers\AdminController::class, 'cards']);
Router::post('/admin/cards/generate', [\App\Controllers\AdminController::class, 'generateCardsProcess']);
Router::post('/admin/cards/update-status', [\App\Controllers\AdminController::class, 'updateCardStatusProcess']);
Router::post('/admin/cards/reassign', [\App\Controllers\AdminController::class, 'reassignCardProcess']);

Router::get('/admin/users', [\App\Controllers\AdminController::class, 'users']);
Router::post('/admin/users/add-seller', [\App\Controllers\AdminController::class, 'addSellerProcess']);
Router::post('/admin/users/toggle-seller', [\App\Controllers\AdminController::class, 'toggleSellerProcess']);
Router::post('/admin/users/toggle-block', [\App\Controllers\AdminController::class, 'toggleUserBlockProcess']);

Router::get('/admin/bios', [\App\Controllers\AdminController::class, 'bios']);
Router::get('/admin/bios/edit/{id}', [\App\Controllers\AdminController::class, 'editBio']);
Router::post('/admin/bios/save', [\App\Controllers\AdminController::class, 'saveBioProcess']);

Router::get('/admin/settings', [\App\Controllers\AdminController::class, 'settings']);
Router::post('/admin/settings/save', [\App\Controllers\AdminController::class, 'saveSettingsProcess']);
Router::post('/admin/settings/test-sms', [\App\Controllers\AdminController::class, 'testSmsProcess']);
Router::get('/admin/guide', [\App\Controllers\AdminController::class, 'guide']);

// 3. Seller Panel
Router::get('/seller', [\App\Controllers\SellerController::class, 'dashboard']);
Router::get('/seller/assign', [\App\Controllers\SellerController::class, 'assignView']);
Router::post('/seller/assign', [\App\Controllers\SellerController::class, 'assignProcess']);
Router::get('/seller/history', [\App\Controllers\SellerController::class, 'history']);

// 4. Bio Owner Dashboard
Router::get('/dashboard', [\App\Controllers\DashboardController::class, 'index']);
Router::get('/dashboard/profile', [\App\Controllers\DashboardController::class, 'profileView']);
Router::post('/dashboard/profile', [\App\Controllers\DashboardController::class, 'profileSaveProcess']);
Router::get('/dashboard/theme', [\App\Controllers\DashboardController::class, 'themeView']);
Router::post('/dashboard/theme', [\App\Controllers\DashboardController::class, 'themeSaveProcess']);

Router::get('/dashboard/blocks', [\App\Controllers\DashboardController::class, 'blocksView']);
Router::post('/dashboard/blocks/add', [\App\Controllers\DashboardController::class, 'addBlockProcess']);
Router::post('/dashboard/blocks/delete', [\App\Controllers\DashboardController::class, 'deleteBlockProcess']);
Router::post('/dashboard/blocks/toggle', [\App\Controllers\DashboardController::class, 'toggleBlockProcess']);

Router::get('/dashboard/analytics', [\App\Controllers\DashboardController::class, 'analyticsView']);

// 5. API Routes
Router::post('/api/send-otp', [\App\Controllers\AuthController::class, 'sendOtpAjax']);
Router::post('/api/track-click', [\App\Controllers\PublicController::class, 'trackClick']);
Router::post('/api/lead', [\App\Controllers\PublicController::class, 'submitLead']);
Router::post('/api/booking', [\App\Controllers\PublicController::class, 'submitBooking']);

// 6. Public Fallback Route ( /{slug} for bio username or unassigned card )
Router::get('/{slug}', [\App\Controllers\PublicController::class, 'handleSlug']);

// Root redirect
if ($uri === '/' || $uri === '') {
    if (Session::has('user_id')) {
        Response::redirect('/dashboard');
    } else {
        Response::redirect('/login');
    }
}

// Dispatch
Router::dispatch(Request::uri(), Request::method());
