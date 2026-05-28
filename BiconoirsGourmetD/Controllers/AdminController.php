<?php
namespace App\Controllers;

use App\Repositories\DishRepository;
use App\Repositories\ReservationRepository;
use App\Repositories\SurveyRepository;
use App\Repositories\OrderRepository;

class AdminController extends BaseController {

    public function dashboard(): void {
        $dishes       = (new DishRepository())->findAll();
        $reservations = (new ReservationRepository())->findAll();
        $surveys      = (new SurveyRepository())->findAll();
        $orders       = (new OrderRepository())->findAll();
        $this->render('admin/dashboard', compact('dishes', 'reservations', 'surveys', 'orders'));
    }

    public function addDish(): void {
        (new DishRepository())->save([
            'id'          => time(),
            'name'        => $_POST['name'],
            'description' => $_POST['description'],
            'price'       => (float)$_POST['price'],
            'image'       => $_POST['image'],
            'ingredients' => [],
        ]);
        $this->redirect('admin_dashboard');
    }

    public function editDish(): void {
        (new DishRepository())->update($_POST['id'], [
            'name'        => $_POST['name'],
            'description' => $_POST['description'],
            'price'       => (float)$_POST['price'],
            'image'       => $_POST['image'],
        ]);
        $this->redirect('admin_dashboard');
    }

    public function updateOrderStatus(): void {
        if (isset($_GET['id'], $_GET['status'])) {
            (new OrderRepository())->updateStatus($_GET['id'], $_GET['status']);
        }
        $this->redirect('admin_dashboard');
    }
}
