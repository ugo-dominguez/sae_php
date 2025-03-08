<?php
namespace App\Controllers;

use App\Config\Requests;

class SearchController extends BaseController {
    public function search(): void {
        $pageTitle = 'Résultats de recherche';

        Requests::getConnection();
        $restaurantTypes = Requests::getAllRestaurantTypes();
        
        $restaurants = [];
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && (isset($_GET['keywords']) || isset($_GET['city']) || isset($_GET['type']))) {
            $keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : '';
            $city = isset($_GET['city']) ? trim($_GET['city']) : '';
            $type = isset($_GET['type']) ? trim($_GET['type']) : 'restaurant';

            $restaurants = Requests::searchRestaurants($keywords, $city, $type);
        }

        $this->render('search/results', [
            'pageTitle' => $pageTitle,
            'restaurants' => $restaurants,
            'restaurantTypes' => $restaurantTypes
        ]);
    }
}
