<?php
namespace App\Controllers;

use App\Repositories\DishRepository;
use App\Repositories\ReservationRepository;
use App\Repositories\SurveyRepository;

class HomeController extends BaseController {

    public function index(): void {
        $dishes = (new DishRepository())->findAll();
        $this->render('home', compact('dishes'));
    }

    public function about(): void {
        $this->render('about');
    }

    public function locations(): void {
        $this->render('locations');
    }

    public function reservations(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new ReservationRepository())->save([
                'id'       => time(),
                'customer' => $_POST['name'],
                'date'     => $_POST['date'],
                'type'     => $_POST['type'],
            ]);
            $this->redirect('home');
        }
        $this->render('reservations');
    }

    public function survey(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new SurveyRepository())->save([
                'id'       => time(),
                'customer' => $_POST['customer'] ?? 'Anónimo',
                'rating'   => (int)$_POST['rating'],
                'comment'  => $_POST['comment'],
            ]);
            $this->redirect('home');
        }
        $this->render('survey');
    }
}
