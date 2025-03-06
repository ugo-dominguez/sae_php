<?php 
namespace App\Config;

use PDO;
use PDOException;

use App\Config\Database;
use App\Models\Restaurant;

class Requests {
    private static ?PDO $connection = null;
    
    public static function getConnection(): PDO {
        if (self::$connection === null) {
            self::$connection = Database::getConnection();
        }
        return self::$connection;
    }
    
    public static function getRestaurants(int $limit): array {
        try {
            $query = "SELECT * FROM Restaurant LIMIT :limit";
            $stmt = self::$connection->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            $restaurants = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $restaurants[] = new Restaurant($row);
            }
    
            return $restaurants;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des restaurants: " . $e->getMessage());
            return [];
        }
    }
}
