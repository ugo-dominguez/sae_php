<?php

namespace App\Controllers;

use App\Config\Requests;

class RestaurantController extends BaseController {
    public function show(string $id): void {
        $pageTitle = 'Détails du restaurant';

        Requests::getConnection();
        $restaurant = Requests::getRestaurantById((int) $id);

        if (!$restaurant) {
            http_response_code(404);
            echo "Restaurant not found";
            return;
        }

        $this->render('restaurant/details', [
            'pageTitle' => $pageTitle,
            'restaurant' => $restaurant
        ]);
    }
}
