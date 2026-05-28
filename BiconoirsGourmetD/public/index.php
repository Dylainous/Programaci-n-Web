<?php
session_start();
require_once __DIR__ . '/../config.php';

// ---------------------------------------------------------------------------
// Autoloader PSR-style
// index.php está en:  BiconoirsGourmet/public/index.php
// Las clases están en: BiconoirsGourmet/Controllers/, BiconoirsGourmet/Models/, etc.
// __DIR__ = BiconoirsGourmet/public/
// __DIR__ . '/../' = BiconoirsGourmet/   <-- ahí están todas las carpetas
// ---------------------------------------------------------------------------
spl_autoload_register(function (string $class): void {
    $prefix   = 'App\\';
    $base_dir = __DIR__ . '/../';

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) return;

    $relative = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, substr($class, strlen($prefix)));
    $file     = $base_dir . $relative . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// ---------------------------------------------------------------------------
// Importaciones
// ---------------------------------------------------------------------------
use App\Router;
use App\Controllers\HomeController;
use App\Controllers\MenuController;
use App\Controllers\CartController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;

// ---------------------------------------------------------------------------
// Definición de rutas
// ---------------------------------------------------------------------------
$router = new Router();

// Públicas
$router->get ('home',         [HomeController::class, 'index']);
$router->get ('about',        [HomeController::class, 'about']);
$router->get ('locations',    [HomeController::class, 'locations']);
$router->get ('menu',         [MenuController::class, 'index']);
$router->get ('login',        [AuthController::class, 'login']);
$router->post('login',        [AuthController::class, 'login']);
$router->get ('register',     [AuthController::class, 'register']);
$router->post('register',     [AuthController::class, 'register']);
$router->get ('logout',       [AuthController::class, 'logout']);

// Reservaciones y encuestas (públicas)
$router->get ('reservations', [HomeController::class, 'reservations']);
$router->post('reservations', [HomeController::class, 'reservations']);
$router->get ('survey',       [HomeController::class, 'survey']);
$router->post('survey',       [HomeController::class, 'survey']);

// Requieren sesión activa
$router->get ('cart',             [CartController::class, 'index'],    ['auth']);
$router->post('add_to_cart',      [CartController::class, 'add'],      ['auth']);
$router->get ('remove_from_cart', [CartController::class, 'remove'],   ['auth']);
$router->post('checkout',         [CartController::class, 'checkout'], ['auth']);
$router->get ('orders',           [AuthController::class, 'orders'],   ['auth']);

// Requieren rol admin
$router->get ('admin_dashboard',     [AdminController::class, 'dashboard'],         ['admin']);
$router->post('add_dish',            [AdminController::class, 'addDish'],           ['admin']);
$router->post('edit_dish',           [AdminController::class, 'editDish'],          ['admin']);
$router->get ('update_order_status', [AdminController::class, 'updateOrderStatus'], ['admin']);

// ---------------------------------------------------------------------------
// Despacho
// ---------------------------------------------------------------------------
$router->dispatch();
