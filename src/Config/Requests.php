<?php 
namespace App\Config;

use PDO;
use PDOException;
use App\Config\Database;

class Requests {
    private static PDO $connection;
    
    public static function setConnection(): void {
        self::$connection = Database::$connection;
    }
    
    /**
     * Récupère un nombre spécifié de restaurants depuis la base de données
     * 
     * @param int $limit Le nombre de restaurants à récupérer
     * @return array Un tableau contenant les restaurants récupérés
     */
    public static function getRestaurant(int $limit): array {
        try {
            $query = "SELECT * FROM Restaurant LIMIT :limit";
            $stmt = self::$connection->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Gestion de l'erreur
            error_log("Erreur lors de la récupération des restaurants: " . $e->getMessage());
            return [];
        }
    }
}
