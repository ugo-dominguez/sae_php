<?php

namespace App\Controllers;

use App\Config\Requests;

class ProfileController extends BaseController {
    public function show(string $id): void {
        $pageTitle = 'Détails de l\'utilisateur';

        Requests::getConnection();
        $user = Requests::getUserById((int) $id);

        $reviews = Requests::getReviewsOfUser((int) $id);
        foreach ($reviews as $review) {
            $restaurant = Requests::getRestaurantById($review->getIdRestau());
            if ($restaurant) {
                $review->setRestaurant($restaurant);
            }
        }

        if (!$user) {
            http_response_code(404);
            echo "User not found";
            return;
        }

        $this->render('profile/profile', [
            'pageTitle' => $pageTitle,
            'reviews' => $reviews,
            'user' => $user,
        ]);
    }
}
