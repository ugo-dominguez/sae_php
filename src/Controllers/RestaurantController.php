<?php

namespace App\Controllers;

use App\Config\Requests;
use App\Config\Database;

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
    
        $error = $_SESSION['error'] ?? null;
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    
        $reviews = Requests::getReviewsForRestaurant((int) $id);
    
        $this->render('restaurant/details', [
            'pageTitle' => $pageTitle,
            'restaurant' => $restaurant,
            'reviews' => $reviews,
            'error' => $error,
            'success' => $success
        ]);
    }

    public function submitReview(string $id): void {
        Requests::getConnection();
        $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 5]
        ]);
        $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);

        if (!isset($rating) || $rating === false) {
            $_SESSION['error'] = "Veuillez sélectionner une note entre 1 et 5 étoiles.";
            header("Location: /restaurant/{$id}");
            exit;
        }
        
        if (!empty(Requests::getReviewsOfUserForRestaurant($_SESSION['user_id'], (int) $id))) {
            $_SESSION['error'] = "Vous avez déjà laissé un avis sur ce restaurant.";
            header("Location: /restaurant/{$id}");
            exit;
        } 
        
        try {
            Database::insertReview($_SESSION['user_id'], (int) $id, $rating, $comment);
            $_SESSION['success'] = "Votre avis a été publié avec succès!";
        } catch (Exception $e) {
            error_log('Review submission error: ' . $e->getMessage());
            $_SESSION['error'] = "Une erreur est survenue lors de la publication de votre avis.";
        }

        header("Location: /restaurant/{$id}");
        exit;
    }
}
