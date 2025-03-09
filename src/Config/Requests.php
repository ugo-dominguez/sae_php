<?php 

namespace App\Config;

use PDO;
use PDOException;
use App\Config\Database;
use App\Models\Restaurant;
use App\Models\User;
use App\Models\Reviewed;

class Requests {
    private static ?PDO $connection = null;
    
    public static function getConnection(): PDO {
        if (self::$connection === null) {
            self::$connection = Database::getConnection();
        }
        return self::$connection;
    }

    public static function makeRestaurants($statement): array {
        try {
            $restaurants = [];
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $restaurants[] = new Restaurant($row);
            }
            return $restaurants;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des restaurants: " . $e->getMessage());
            return [];
        }
    }
    
    public static function getRestaurants(int $limit): array {
        try {
            $query = "SELECT * FROM Restaurant LIMIT :limit";
            $stmt = self::$connection->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return self::makeRestaurants($stmt);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des restaurants: " . $e->getMessage());
            return [];
        }
    }

    public static function getRestaurantById(int $id) {
        try {
            $query = "SELECT * FROM Restaurant WHERE idRestau = ?";
            $stmt = self::$connection->prepare($query);
            $stmt->execute([$id]);

            return self::makeRestaurants($stmt)[0];
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération d'un restaurant: " . $e->getMessage());
            return null;
        }
    }

    public static function searchRestaurants($keywords, $city, $type) {
        try {
            $sql = "SELECT * FROM Restaurant WHERE 1=1";
            $params = [];
        
            if (!empty($keywords)) {
                $sql .= " AND nameR LIKE :keywords";
                $params[':keywords'] = '%' . $keywords . '%';
            }
        
            if (!empty($city)) {
                $sql .= " AND city LIKE :city";
                $params[':city'] = '%' . $city . '%';
            }
        
            if (!empty($type)) {
                $sql .= " AND typeR = :type";
                $params[':type'] = $type;
            }

            $stmt = self::$connection->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }
            $stmt->execute($params);
        
            return self::makeRestaurants($stmt);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des restaurants: " . $e->getMessage());
            return [];
        }
    }

    public static function getAllRestaurantTypes(): array {
        try {
            $sql = "SELECT DISTINCT typeR FROM Restaurant WHERE typeR IS NOT NULL AND typeR != ''";
            $stmt = self::$connection->prepare($sql);
            $stmt->execute();
        
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des restaurants: " . $e->getMessage());
            return [];
        }
    }

    public static function getUserById(int $id) : User {
        try {
            $query = "SELECT * FROM User WHERE idUser = ?";
            $stmt = self::$connection->prepare($query);
            $stmt->execute([$id]);
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return new User($row['idUser'], $row['username'], $row['password']);

        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération d'un utilisateur: " . $e->getMessage());
            return null;
        }
    }

    public static function getReviewsOfUser(int $userId) : array {
        try {
            $query = "SELECT * FROM Reviewed WHERE idUser = ?";
            $stmt = self::$connection->prepare($query);
            $stmt->execute([$userId]);
            
            $reviews = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $reviews[] = new Reviewed($row['idUser'], $row['idRestau'], $row['note'], $row['comment']);
            }
            return $reviews;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des avis: " . $e->getMessage());
            return [];
        }
    }

    public static function getReviewsForRestaurant(int $restaurantId) : array {
        try {
            $query = "SELECT * FROM Reviewed WHERE idRestau = ?";
            $stmt = self::$connection->prepare($query);
            $stmt->execute([$restaurantId]);
            
            $reviews = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $reviews[] = new Reviewed($row['idUser'], $row['idRestau'], $row['note'], $row['comment']);
            }
            return $reviews;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des avis: " . $e->getMessage());
            return [];
        }
    }
}
