<?php
namespace App\Controllers;

class CartController {

    /**
     * Muestra el carrito.
     * GUARD: Requiere sesión activa. Si no hay sesión, redirige a la
     * página de aviso con la URL de origen para que el usuario sepa
     * por qué fue redirigido.
     */
    public function index() {
        if (!isset($_SESSION['user'])) {
            // Guardamos la URL que el usuario intentaba acceder
            $_SESSION['intended_url'] = 'index.php?action=cart';
            header('Location: index.php?action=redirect_notice&from=cart');
            exit();
        }

        $cart = $_SESSION['cart'] ?? [];
        require_once __DIR__ . '/../Views/cart.php';
    }

    public function add() {
        if (!isset($_SESSION['user'])) {
            $_SESSION['intended_url'] = 'index.php?action=cart';
            header('Location: index.php?action=redirect_notice&from=cart');
            exit();
        }

        $dishId   = $_POST['dish_id']   ?? null;
        $dishName = $_POST['dish_name'] ?? '';
        $price    = $_POST['price']     ?? 0;
        $quantity = (int)($_POST['quantity'] ?? 1);

        if ($dishId) {
            if (isset($_SESSION['cart'][$dishId])) {
                $_SESSION['cart'][$dishId]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$dishId] = [
                    'name'     => $dishName,
                    'price'    => (float)$price,
                    'quantity' => $quantity
                ];
            }
        }

        header('Location: index.php?action=cart');
        exit();
    }

    public function remove() {
        $dishId = $_GET['id'] ?? null;
        if ($dishId && isset($_SESSION['cart'][$dishId])) {
            unset($_SESSION['cart'][$dishId]);
        }
        header('Location: index.php?action=cart');
        exit();
    }
}
