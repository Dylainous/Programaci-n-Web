<?php
namespace App\Controllers;

use App\Repositories\DishRepository;
use App\Repositories\OrderRepository;

class CartController extends BaseController {

    public function index(): void {
        $cart = $_SESSION['cart'] ?? [];
        $this->render('cart', compact('cart'));
    }

    public function add(): void {
        $id       = $_POST['id'];
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        $dish     = null;

        foreach ((new DishRepository())->findAll() as $d) {
            if ((string)$d['id'] === (string)$id) { $dish = $d; break; }
        }

        if ($dish) {
            $_SESSION['cart'] ??= [];
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$id] = [
                    'name'        => $dish['name'],
                    'price'       => $dish['price'],
                    'quantity'    => $quantity,
                    'ingredients' => $_POST['ingredients'] ?? $dish['ingredients'],
                ];
            }
        }
        $this->redirect('menu');
    }

    public function remove(): void {
        $id = $_GET['id'] ?? null;
        if ($id && isset($_SESSION['cart'][$id])) unset($_SESSION['cart'][$id]);
        $this->redirect('cart');
    }

    public function checkout(): void {
        if (empty($_SESSION['cart'])) $this->redirect('menu');

        $user  = $_SESSION['user'];
        $cart  = $_SESSION['cart'];
        $total = array_reduce($cart, fn(float $c, array $i) => $c + ($i['price'] * $i['quantity']), 0.0);

        $orderId = (new OrderRepository())->save([
            'customer_name'  => $user['name'],
            'customer_email' => $user['email'],
            'items'          => $cart,
            'total'          => $total,
        ]);

        unset($_SESSION['cart']);
        $this->redirectWith('orders', 'confirmed', $orderId);
    }
}
