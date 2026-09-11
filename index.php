<?php

define('EAFD_BASE_DIR', __DIR__);

require_once EAFD_BASE_DIR . '/app/Core/Autoloader.php';
\App\Core\Autoloader::register(EAFD_BASE_DIR);

use App\Core\Config;
use App\Core\Request;
use App\Core\Router;
use App\Core\ErrorHandler;
use Install\Installer;

Config::load(EAFD_BASE_DIR . '/config');
ErrorHandler::register();

if (!Installer::isInstalled() && !str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/install')) {
    header('Location: /install');
    exit;
}

$router = new Router();

// Public Frontend Routes
$router->get('/', [\App\Controllers\HomeController::class, 'index']);
$router->get('/about', [\App\Controllers\PageController::class, 'about']);
$router->get('/contact', [\App\Controllers\PageController::class, 'contact']);
$router->get('/faq', [\App\Controllers\PageController::class, 'faq']);
$router->get('/page/{slug}', [\App\Controllers\PageController::class, 'show']);
$router->get('/services', [\App\Controllers\ServiceController::class, 'index']);
$router->get('/services/{slug}', [\App\Controllers\ServiceController::class, 'detail']);
$router->get('/projects', [\App\Controllers\ProjectController::class, 'index']);
$router->get('/projects/{slug}', [\App\Controllers\ProjectController::class, 'detail']);
$router->get('/lab', [\App\Controllers\LabController::class, 'index']);
$router->get('/lab/tool/{slug}', [\App\Controllers\LabController::class, 'tool']);
$router->get('/lab/quiz/{slug}', [\App\Controllers\LabController::class, 'quiz']);
$router->get('/sitemap.xml', [\App\Controllers\SitemapController::class, 'xml']);
$router->get('/robots.txt', [\App\Controllers\SitemapController::class, 'robots']);

// Store & Checkout Routes
$router->get('/store', [\App\Controllers\Shop\StoreController::class, 'index']);
$router->get('/store/{slug}', [\App\Controllers\Shop\StoreController::class, 'detail']);
$router->get('/cart', [\App\Controllers\Shop\CartController::class, 'index']);
$router->get('/cart/add/{id}', [\App\Controllers\Shop\CartController::class, 'add']);
$router->get('/cart/remove/{id}', [\App\Controllers\Shop\CartController::class, 'remove']);
$router->get('/checkout', [\App\Controllers\Shop\CheckoutController::class, 'index']);
$router->post('/checkout/process', [\App\Controllers\Shop\CheckoutController::class, 'process'], ['csrf']);
$router->get('/checkout/callback', [\App\Controllers\Shop\CheckoutController::class, 'callback']);
$router->get('/download', [\App\Controllers\Shop\DownloadController::class, 'download']);

// Auth & Customer Account Routes
$router->get('/login', [\App\Controllers\AuthController::class, 'showLogin'], ['guest']);
$router->post('/login', [\App\Controllers\AuthController::class, 'login'], ['guest', 'csrf']);
$router->get('/logout', [\App\Controllers\AuthController::class, 'logout']);
$router->get('/account', [\App\Controllers\AccountController::class, 'index'], ['auth']);
$router->get('/account/orders', [\App\Controllers\AccountController::class, 'orders'], ['auth']);
$router->get('/account/licenses', [\App\Controllers\AccountController::class, 'licenses'], ['auth']);

// License REST API Endpoints
$router->get('/api/v1/license/verify', [\App\Controllers\Api\LicenseApiController::class, 'verify']);
$router->post('/api/v1/license/verify', [\App\Controllers\Api\LicenseApiController::class, 'verify']);
$router->post('/api/v1/license/activate', [\App\Controllers\Api\LicenseApiController::class, 'activate']);
$router->post('/api/v1/license/deactivate', [\App\Controllers\Api\LicenseApiController::class, 'deactivate']);
$router->get('/api/v1/license/check', [\App\Controllers\Api\LicenseApiController::class, 'check']);
$router->get('/api/v1/license/update', [\App\Controllers\Api\LicenseApiController::class, 'update']);

// Admin Dashboard Routes
$router->get('/admin', [\App\Controllers\Admin\DashboardController::class, 'index'], ['admin']);
$router->get('/admin/design-studio', [\App\Controllers\Admin\DesignStudioController::class, 'index'], ['admin']);
$router->post('/admin/design-studio/update', [\App\Controllers\Admin\DesignStudioController::class, 'update'], ['admin', 'csrf']);
$router->post('/admin/design-studio/reset', [\App\Controllers\Admin\DesignStudioController::class, 'reset'], ['admin', 'csrf']);

$router->get('/admin/pages', [\App\Controllers\Admin\PageController::class, 'index'], ['admin']);
$router->get('/admin/pages/create', [\App\Controllers\Admin\PageController::class, 'create'], ['admin']);
$router->get('/admin/pages/edit/{id}', [\App\Controllers\Admin\PageController::class, 'edit'], ['admin']);
$router->post('/admin/pages/save/{id}', [\App\Controllers\Admin\PageController::class, 'save'], ['admin', 'csrf']);
$router->post('/admin/pages/delete/{id}', [\App\Controllers\Admin\PageController::class, 'delete'], ['admin', 'csrf']);

$router->get('/admin/pages/{id}/builder', [\App\Controllers\Admin\PageBuilderController::class, 'edit'], ['admin']);
$router->post('/admin/pages/{id}/builder/save', [\App\Controllers\Admin\PageBuilderController::class, 'save'], ['admin', 'csrf']);
$router->post('/admin/pages/{id}/builder/add-section', [\App\Controllers\Admin\PageBuilderController::class, 'addSection'], ['admin', 'csrf']);
$router->get('/admin/pages/{page_id}/builder/delete-section/{section_id}', [\App\Controllers\Admin\PageBuilderController::class, 'deleteSection'], ['admin', 'csrf']);

$router->get('/admin/services', [\App\Controllers\Admin\ServiceController::class, 'index'], ['admin']);
$router->get('/admin/services/create', [\App\Controllers\Admin\ServiceController::class, 'create'], ['admin']);
$router->get('/admin/services/edit/{id}', [\App\Controllers\Admin\ServiceController::class, 'edit'], ['admin']);
$router->post('/admin/services/save/{id}', [\App\Controllers\Admin\ServiceController::class, 'save'], ['admin', 'csrf']);
$router->post('/admin/services/delete/{id}', [\App\Controllers\Admin\ServiceController::class, 'delete'], ['admin', 'csrf']);

$router->get('/admin/projects', [\App\Controllers\Admin\ProjectController::class, 'index'], ['admin']);
$router->get('/admin/projects/create', [\App\Controllers\Admin\ProjectController::class, 'create'], ['admin']);
$router->get('/admin/projects/edit/{id}', [\App\Controllers\Admin\ProjectController::class, 'edit'], ['admin']);
$router->post('/admin/projects/save/{id}', [\App\Controllers\Admin\ProjectController::class, 'save'], ['admin', 'csrf']);
$router->post('/admin/projects/delete/{id}', [\App\Controllers\Admin\ProjectController::class, 'delete'], ['admin', 'csrf']);

$router->get('/admin/products', [\App\Controllers\Admin\ProductController::class, 'index'], ['admin']);
$router->get('/admin/products/create', [\App\Controllers\Admin\ProductController::class, 'create'], ['admin']);
$router->get('/admin/products/edit/{id}', [\App\Controllers\Admin\ProductController::class, 'edit'], ['admin']);
$router->post('/admin/products/save/{id}', [\App\Controllers\Admin\ProductController::class, 'save'], ['admin', 'csrf']);
$router->post('/admin/products/delete/{id}', [\App\Controllers\Admin\ProductController::class, 'delete'], ['admin', 'csrf']);

$router->get('/admin/licenses', [\App\Controllers\Admin\LicenseController::class, 'index'], ['admin']);
$router->get('/admin/licenses/edit/{id}', [\App\Controllers\Admin\LicenseController::class, 'edit'], ['admin']);
$router->post('/admin/licenses/save/{id}', [\App\Controllers\Admin\LicenseController::class, 'save'], ['admin', 'csrf']);
$router->post('/admin/licenses/revoke/{id}', [\App\Controllers\Admin\LicenseController::class, 'revoke'], ['admin', 'csrf']);

$router->get('/admin/tools', [\App\Controllers\Admin\ToolController::class, 'index'], ['admin']);
$router->get('/admin/tools/create', [\App\Controllers\Admin\ToolController::class, 'create'], ['admin']);
$router->get('/admin/tools/edit/{id}', [\App\Controllers\Admin\ToolController::class, 'edit'], ['admin']);
$router->post('/admin/tools/save/{id}', [\App\Controllers\Admin\ToolController::class, 'save'], ['admin', 'csrf']);
$router->post('/admin/tools/delete/{id}', [\App\Controllers\Admin\ToolController::class, 'delete'], ['admin', 'csrf']);

$router->get('/admin/quizzes', [\App\Controllers\Admin\QuizController::class, 'index'], ['admin']);
$router->get('/admin/quizzes/create', [\App\Controllers\Admin\QuizController::class, 'create'], ['admin']);
$router->get('/admin/quizzes/edit/{id}', [\App\Controllers\Admin\QuizController::class, 'edit'], ['admin']);
$router->post('/admin/quizzes/save/{id}', [\App\Controllers\Admin\QuizController::class, 'save'], ['admin', 'csrf']);
$router->post('/admin/quizzes/delete/{id}', [\App\Controllers\Admin\QuizController::class, 'delete'], ['admin', 'csrf']);

$router->get('/admin/users', [\App\Controllers\Admin\UserController::class, 'index'], ['admin']);
$router->get('/admin/users/edit/{id}', [\App\Controllers\Admin\UserController::class, 'edit'], ['admin']);
$router->post('/admin/users/save/{id}', [\App\Controllers\Admin\UserController::class, 'save'], ['admin', 'csrf']);

$router->get('/admin/logs', [\App\Controllers\Admin\LogController::class, 'index'], ['admin']);

$request = new Request();
$router->dispatch($request);
