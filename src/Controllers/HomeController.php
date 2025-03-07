<?php
namespace App\Controllers;

use App\Config\Requests;

class HomeController extends BaseController {
    public function home(): void {
        $pageTitle = 'Baratie';

        Requests::getConnection();
        $restaurants = Requests::getRestaurants(5);
        $restaurantTypes = Requests::getAllRestaurantTypes();

        $this->render('home/homepage', [
            'pageTitle' => $pageTitle,
            'restaurants' => $restaurants,
            'restaurantTypes' => $restaurantTypes
        ]);
    }
}