<?php
namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\OrderRepository;

class AuthController extends BaseController {

    public function login(): void {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = (new UserRepository())->findByCredentials($_POST['email'], $_POST['password']);
            if ($user) {
                $_SESSION['user'] = $user;
                $this->redirect($_GET['redirect'] ?? 'home');
            }
            $error = 'Credenciales incorrectas';
        }
        $this->render('login', compact('error'));
    }

    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = [
                'name'     => $_POST['name'],
                'email'    => $_POST['email'],
                'password' => $_POST['password'],
                'role'     => 'customer',
            ];
            (new UserRepository())->save($user);
            $_SESSION['user'] = $user;
            $this->redirect('home');
        }
        $this->render('register');
    }

    public function logout(): void {
        session_destroy();
        $this->redirect('home');
    }

    public function orders(): void {
        $orders    = (new OrderRepository())->findByUser($_SESSION['user']['email']);
        $confirmed = $_GET['confirmed'] ?? null;
        $this->render('orders', compact('orders', 'confirmed'));
    }
}
