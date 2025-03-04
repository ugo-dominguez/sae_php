<?php
namespace App\Config;

class Database {
    private static $instance = null;
    private \PDO $connection;
    
    public function __construct() {
        $config = [
            'server' => 'servinfo-maria',
            'dbname' => 'DBthomas',
            'username' => 'thomas',
            'password' => 'thomas',
        ];

        try {
            $dsn = "mysql:host={$config['server']};dbname={$config['dbname']}";
            $this->connection = new \PDO($dsn, $config['username'], $config['password'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false
            ]);
            $dsn = "mysql:server={$config['server']};dbname={$config['dbname']}}";
        } catch (\PDOException $e) {
            throw new \Exception("Échec de connexion à la BD : " . $e->getMessage());
        }
    }
    
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection(): \PDO {
        return $this->connection;
    }

    public function restaurantsLoaded() {
        try {
            $stmt = $this->connection->query("SELECT COUNT(*) FROM Restaurant");
            $count = (int) $stmt->fetchColumn();
            return ($count > 0);
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors d'une requête sur Restaurant: " . $e->getMessage());
        }
    }

    public function insertRestaurants(array $data) {
        try {
            foreach ($data as $restaurant) {
                $sql = "INSERT INTO Restaurant (address, nameR, schedule, website, phone, accessibl, delivery)
                        VALUES (:address, :nameR, :schedule, :website, :phone, :accessible, :delivery)";
                
                $stmt = $this->connection->prepare($sql);
                $stmt->execute([
                    ':address' => $restaurant['commune'] ?? null,
                    ':nameR' => $restaurant['name'] ?? null,
                    ':schedule' => $restaurant['opening_hours'] ?? null,
                    ':website' => $restaurant['website'] ?? null,
                    ':phone' => $restaurant['phone'] ?? null,
                    ':accessible' => ($restaurant['wheelchair'] === 'yes') ? 1 : 0,
                    ':delivery' => ($restaurant['delivery'] === 'yes') ? 1 : 0,
                ]);
            }
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de l'insertion du restaurant: " . $e->getMessage());
        }
    }

    public function createTables() {
        try {
            $sql = "
            CREATE TABLE IF NOT EXISTS User (
                idUser INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL,
                password VARCHAR(255) NOT NULL
            );
            
            CREATE TABLE IF NOT EXISTS FoodType (
                type VARCHAR(50) PRIMARY KEY
            );
            
            CREATE TABLE IF NOT EXISTS Restaurant (
                idRestau INT AUTO_INCREMENT PRIMARY KEY,
                address VARCHAR(255),
                nameR VARCHAR(100) NOT NULL,
                schedule VARCHAR(255),
                website VARCHAR(255),
                phone VARCHAR(20),
                accessibl TINYINT(1) NOT NULL DEFAULT 0,
                delivery TINYINT(1) NOT NULL DEFAULT 0
            );
            
            CREATE TABLE IF NOT EXISTS Photo (
                idPhoto INT AUTO_INCREMENT PRIMARY KEY,
                image VARCHAR(255) NOT NULL
            );
            
            CREATE TABLE IF NOT EXISTS Serves (
                idRestau INT,
                type VARCHAR(50),
                PRIMARY KEY (idRestau, type),
                FOREIGN KEY (idRestau) REFERENCES Restaurant(idRestau) ON DELETE CASCADE,
                FOREIGN KEY (type) REFERENCES FoodType(type) ON DELETE CASCADE
            );
            
            CREATE TABLE IF NOT EXISTS Prefers (
                idUser INT,
                type VARCHAR(50),
                PRIMARY KEY (idUser, type),
                FOREIGN KEY (idUser) REFERENCES User(idUser) ON DELETE CASCADE,
                FOREIGN KEY (type) REFERENCES FoodType(type) ON DELETE CASCADE
            );
            
            CREATE TABLE IF NOT EXISTS Illustrates (
                idPhoto INT,
                idRestau INT,
                PRIMARY KEY (idPhoto, idRestau),
                FOREIGN KEY (idPhoto) REFERENCES Photo(idPhoto) ON DELETE CASCADE,
                FOREIGN KEY (idRestau) REFERENCES Restaurant(idRestau) ON DELETE CASCADE
            );
            
            CREATE TABLE IF NOT EXISTS Reviewed (
                idUser INT,
                idRestau INT,
                note INT CHECK (note BETWEEN 0 AND 5),
                comment TEXT,
                PRIMARY KEY (idUser, idRestau),
                FOREIGN KEY (idUser) REFERENCES User(idUser) ON DELETE CASCADE,
                FOREIGN KEY (idRestau) REFERENCES Restaurant(idRestau) ON DELETE CASCADE
            );
            
            CREATE TABLE IF NOT EXISTS Likes (
                idUser INT,
                idRestau INT,
                PRIMARY KEY (idUser, idRestau),
                FOREIGN KEY (idUser) REFERENCES User(idUser) ON DELETE CASCADE,
                FOREIGN KEY (idRestau) REFERENCES Restaurant(idRestau) ON DELETE CASCADE
            );
            ";
            $this->connection->exec($sql);
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la création des tables: " . $e->getMessage());
        }
    }

    public function deleteTables() {
        try {
            $sql = "
            DROP TABLE IF EXISTS Likes;
            DROP TABLE IF EXISTS Reviewed;
            DROP TABLE IF EXISTS Illustrates;
            DROP TABLE IF EXISTS Prefers;
            DROP TABLE IF EXISTS Serves;
            DROP TABLE IF EXISTS Photo;
            DROP TABLE IF EXISTS Restaurant;
            DROP TABLE IF EXISTS FoodType;
            DROP TABLE IF EXISTS User;
            ";
            $this->connection->exec($sql);
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la suppression des tables: " . $e->getMessage());
        }
    }
}
