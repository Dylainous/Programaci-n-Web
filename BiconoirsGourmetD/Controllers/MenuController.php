<?php
namespace App\Controllers;

use App\Repositories\DishRepository;

class MenuController extends BaseController {

    public function index(): void {
        $dishes = (new DishRepository())->findAll();
        $this->render('menu', compact('dishes'));
    }
}
