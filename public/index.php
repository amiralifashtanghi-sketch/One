<?php
// Check for Installer lock
if (file_exists(__DIR__ . '/../install') && !file_exists(__DIR__ . '/../install/installed.lock')) {
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    if (strpos($uri, '/install') === false) {
        header('Location: /install/');
        exit;
    }
}

require_once __DIR__ . '/../app/Core/Autoloader.php';
\App\Core\Autoloader::register();

// Load Routes & Bootstrap
$router = new \App\Core\Router();

// Define core public routes
$router->get('', [\App\Controllers\HomeController::class, 'index']);
$router->get('/', [\App\Controllers\HomeController::class, 'index']);
$router->get('/services', [\App\Controllers\ServiceController::class, 'index']);
$router->get('/services/{slug}', [\App\Controllers\ServiceController::class, 'show']);
$router->get('/portfolio', [\App\Controllers\PortfolioController::class, 'index']);
$router->get('/portfolio/{slug}', [\App\Controllers\PortfolioController::class, 'show']);
$router->get('/case-studies/{slug}', [\App\Controllers\CaseStudyController::class, 'show']);
$router->get('/lab', [\App\Controllers\LabController::class, 'index']);
$router->get('/audit', [\App\Controllers\AuditController::class, 'index']);
$router->post('/api/audit', [\App\Controllers\AuditController::class, 'analyze']);
$router->get('/start-project', [\App\Controllers\ProjectController::class, 'index']);
$router->post('/api/start-project', [\App\Controllers\ProjectController::class, 'submit']);

// Admin Auth & Dashboard Routes
$router->get('/admin', [\App\Controllers\Admin\DashboardController::class, 'index']);
$router->get('/admin/login', [\App\Controllers\Admin\AuthController::class, 'loginForm']);
$router->post('/admin/login', [\App\Controllers\Admin\AuthController::class, 'login']);
$router->get('/admin/logout', [\App\Controllers\Admin\AuthController::class, 'logout']);

// Admin Pages Management Routes
$router->get('/admin/pages', [\App\Controllers\Admin\PagesController::class, 'index']);
$router->get('/admin/pages/create', [\App\Controllers\Admin\PagesController::class, 'create']);
$router->post('/admin/pages/store', [\App\Controllers\Admin\PagesController::class, 'store']);
$router->get('/admin/pages/edit/{id}', [\App\Controllers\Admin\PagesController::class, 'edit']);
$router->post('/admin/pages/update/{id}', [\App\Controllers\Admin\PagesController::class, 'update']);
$router->get('/admin/pages/delete/{id}', [\App\Controllers\Admin\PagesController::class, 'delete']);

// Admin Services Management Routes
$router->get('/admin/services', [\App\Controllers\Admin\ServicesController::class, 'index']);
$router->get('/admin/services/create', [\App\Controllers\Admin\ServicesController::class, 'create']);
$router->post('/admin/services/store', [\App\Controllers\Admin\ServicesController::class, 'store']);
$router->get('/admin/services/edit/{id}', [\App\Controllers\Admin\ServicesController::class, 'edit']);
$router->post('/admin/services/update/{id}', [\App\Controllers\Admin\ServicesController::class, 'update']);
$router->get('/admin/services/delete/{id}', [\App\Controllers\Admin\ServicesController::class, 'delete']);

// Admin Portfolio Management Routes
$router->get('/admin/portfolio', [\App\Controllers\Admin\PortfolioController::class, 'index']);
$router->get('/admin/portfolio/create', [\App\Controllers\Admin\PortfolioController::class, 'create']);
$router->post('/admin/portfolio/store', [\App\Controllers\Admin\PortfolioController::class, 'store']);
$router->get('/admin/portfolio/edit/{id}', [\App\Controllers\Admin\PortfolioController::class, 'edit']);
$router->post('/admin/portfolio/update/{id}', [\App\Controllers\Admin\PortfolioController::class, 'update']);
$router->get('/admin/portfolio/delete/{id}', [\App\Controllers\Admin\PortfolioController::class, 'delete']);

// Admin Lab Routes
$router->get('/admin/lab', [\App\Controllers\Admin\LabController::class, 'index']);
$router->get('/admin/lab/create', [\App\Controllers\Admin\LabController::class, 'create']);
$router->post('/admin/lab/store', [\App\Controllers\Admin\LabController::class, 'store']);
$router->get('/admin/lab/edit/{id}', [\App\Controllers\Admin\LabController::class, 'edit']);
$router->post('/admin/lab/update/{id}', [\App\Controllers\Admin\LabController::class, 'update']);
$router->get('/admin/lab/delete/{id}', [\App\Controllers\Admin\LabController::class, 'delete']);

// Admin Leads Routes
$router->get('/admin/leads', [\App\Controllers\Admin\LeadsController::class, 'index']);
$router->get('/admin/leads/show/{id}', [\App\Controllers\Admin\LeadsController::class, 'show']);
$router->post('/admin/leads/update-status/{id}', [\App\Controllers\Admin\LeadsController::class, 'updateStatus']);
$router->get('/admin/leads/delete/{id}', [\App\Controllers\Admin\LeadsController::class, 'delete']);

// Admin FAQ, SEO & Health Routes
$router->get('/admin/faq', [\App\Controllers\Admin\FaqController::class, 'index']);
$router->post('/admin/faq/store', [\App\Controllers\Admin\FaqController::class, 'store']);
$router->get('/admin/faq/delete/{id}', [\App\Controllers\Admin\FaqController::class, 'delete']);
$router->get('/admin/seo', [\App\Controllers\Admin\SeoController::class, 'index']);
$router->post('/admin/seo/update', [\App\Controllers\Admin\SeoController::class, 'update']);
$router->get('/admin/health', [\App\Controllers\Admin\HealthController::class, 'index']);

// Dispatch Request
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$router->dispatch($requestMethod, $requestUri);
