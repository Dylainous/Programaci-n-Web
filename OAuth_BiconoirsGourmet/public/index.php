<?php
session_start();

// Cargar variables de entorno locales (solo existe en local, no en Render)
if (file_exists(__DIR__ . '/../config/env.php')) {
    require_once __DIR__ . '/../config/env.php';
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../config/oauth.php';   // ← Credenciales y constantes OAuth

use App\Controllers\HomeController;
use App\Controllers\MenuController;
use App\Controllers\CartController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;

$action = $_GET['action'] ?? 'home';

switch ($action) {

    // ── Páginas públicas ────────────────────────────────────────────────────
    case 'home':
        (new HomeController())->index();
        break;

    case 'menu':
        // El menú es público: cualquiera puede verlo sin sesión
        (new MenuController())->index();
        break;

    case 'about':
        (new HomeController())->about();
        break;

    case 'locations':
        (new HomeController())->locations();
        break;

    case 'survey':
        (new HomeController())->survey();
        break;

    // ── Autenticación OAuth Google ──────────────────────────────────────────
    case 'login':
        // Muestra la página de login (botón "Continuar con Google")
        (new AuthController())->login();
        break;

    case 'oauth_redirect':
        // FRONT END → redirige al usuario a Google para que se autentique
        (new AuthController())->googleRedirect();
        break;

    case 'oauth_callback':
        // BACK END → Google regresa aquí con el código de autorización
        // Se valida el state, se intercambia el code por token, se crea sesión
        (new AuthController())->googleCallback();
        break;

    case 'logout':
        (new AuthController())->logout();
        break;

    // ── Página de redireccionamiento ────────────────────────────────────────
    case 'redirect_notice':
        // Se muestra cuando alguien intenta acceder a una sección protegida
        // sin tener sesión activa (ej: copió la URL del carrito y cerró sesión)
        require_once __DIR__ . '/../app/Views/redirect_notice.php';
        break;

    // ── Páginas protegidas (requieren sesión) ───────────────────────────────
    case 'cart':
        // El guard está dentro del CartController
        (new CartController())->index();
        break;

    case 'add_to_cart':
        (new CartController())->add();
        break;

    case 'remove_from_cart':
        (new CartController())->remove();
        break;

    case 'reservations':
        (new HomeController())->reservations();
        break;

    case 'orders':
        if (!isset($_SESSION['user'])) {
            $_SESSION['intended_url'] = 'index.php?action=orders';
            header('Location: index.php?action=redirect_notice&from=orders');
            exit();
        }
        $orderDate = $_GET['date'] ?? null;
        $orders = \App\Models\Order::getUserHistory($_SESSION['user']['email'], $orderDate);
        require_once __DIR__ . '/../app/Views/orders.php';
        break;

    case 'checkout':
        if (!isset($_SESSION['user'])) {
            $_SESSION['intended_url'] = 'index.php?action=checkout';
            header('Location: index.php?action=redirect_notice&from=checkout');
            exit();
        }
        if (empty($_SESSION['cart'])) {
            header('Location: index.php?action=menu');
            exit();
        }

        $orderId = \App\Models\Order::add([
            'customer_name'  => $_SESSION['user']['name'],
            'customer_email' => $_SESSION['user']['email'],
            'items'          => $_SESSION['cart'],
            'total'          => array_reduce($_SESSION['cart'], function ($carry, $item) {
                return $carry + ($item['price'] * $item['quantity']);
            }, 0)
        ]);

        unset($_SESSION['cart']);
        echo "<script>alert('¡Pedido #$orderId confirmado! En breve estará listo.'); window.location.href='index.php?action=orders';</script>";
        break;

    // ── Panel de administración ─────────────────────────────────────────────
    case 'admin_dashboard':
        (new AdminController())->dashboard();
        break;

    case 'add_dish':
        (new AdminController())->addDish();
        break;

    case 'edit_dish':
        (new AdminController())->editDish();
        break;

    case 'delete_dish':
        (new AdminController())->deleteDish();
        break;

    case 'update_order_status':
        (new AdminController())->updateOrderStatus();
        break;

    case 'admin_reservations':
        (new AdminController())->reservations();
        break;

    case 'admin_surveys':
        (new AdminController())->surveys();
        break;

    case 'update_reservation_status':
        (new AdminController())->updateReservationStatus();
        break;

    case 'cancel_reservation':
        (new HomeController())->cancelReservation();
        break;

    case 'inventory':
        (new \App\Controllers\InventoryController())->index();
        break;

    case 'store_supply':
        (new \App\Controllers\InventoryController())->storeBatch();
        break;

    case 'edit_ingredient':
        (new \App\Controllers\InventoryController())->editIngredient();
        break;

    case 'delete_ingredient':
        (new \App\Controllers\InventoryController())->deleteIngredient();
        break;

    default:
        (new HomeController())->index();
        break;
}
