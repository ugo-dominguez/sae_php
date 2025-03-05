<?php
namespace App\Config;

use PDO;
use PDOException;

class Database {
    private PDO $connection;
    
    public function __construct(string $dbPath='database.db') {
        try {
            $this->connection = new PDO("sqlite:" . $dbPath);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw new \Exception("Échec de connexion à la BD : " . $e->getMessage());
        }
    }
    
    public function getConnection(): PDO {
        return $this->connection;
    }

    public function insertRestaurants(array $data) {
        try {
            $restaurantsToInsert = array_map(function($restaurant) {
                return [
                    'address' => $restaurant['commune'] ?? null,
                    'nameR' => $restaurant['name'] ?? null,
                    'schedule' => $restaurant['opening_hours'] ?? null,
                    'website' => $restaurant['website'] ?? null,
                    'phone' => $restaurant['phone'] ?? null,
                    'accessibl' => ($restaurant['wheelchair'] === 'yes') ? 1 : 0,
                    'delivery' => ($restaurant['delivery'] === 'yes') ? 1 : 0
                ];
            }, $data);

            $this->insertInto('Restaurant', $restaurantsToInsert);
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de l'insertion des restaurants: " . $e->getMessage());
        }
    }

    public function insertInto(string $tableName, array $dataSet) {
        try {
            $columns = implode(', ', array_keys($dataSet[0]));
            $placeholders = implode(', ', array_fill(0, count($dataSet[0]), '?'));
            $sql = "INSERT INTO {$tableName} ({$columns}) VALUES ({$placeholders})";
            $stmt = $this->connection->prepare($sql);

            $this->connection->beginTransaction();
            foreach ($dataSet as $data) {
                $stmt->execute(array_values($data));
            }
            $this->connection->commit();
            
        } catch (\PDOException $e) {
            $this->connection->rollBack();
            throw new \Exception("Erreur lors de l'insertion dans {$tableName}: " . $e->getMessage());
        }
    }
    
    public function createTables() {
        try {
            $this->connection->beginTransaction();
    
            $this->connection->exec("CREATE TABLE IF NOT EXISTS User (
                idUser INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT NOT NULL,
                password TEXT NOT NULL
            )");
    
            $this->connection->exec("CREATE TABLE IF NOT EXISTS FoodType (
                type TEXT PRIMARY KEY
            )");
    
            $this->connection->exec("CREATE TABLE IF NOT EXISTS Restaurant (
                idRestau INTEGER PRIMARY KEY AUTOINCREMENT,
                address TEXT,
                nameR TEXT NOT NULL,
                schedule TEXT,
                website TEXT,
                phone TEXT,
                accessibl INTEGER NOT NULL DEFAULT 0, 
                delivery INTEGER NOT NULL DEFAULT 0
            )");
    
            $this->connection->exec("CREATE TABLE IF NOT EXISTS Photo (
                idPhoto INTEGER PRIMARY KEY AUTOINCREMENT,
                image TEXT NOT NULL
            )");
    
            $this->connection->exec("CREATE TABLE IF NOT EXISTS Serves (
                idRestau INTEGER,
                type TEXT,
                PRIMARY KEY (idRestau, type),
                FOREIGN KEY (idRestau) REFERENCES Restaurant(idRestau) ON DELETE CASCADE,
                FOREIGN KEY (type) REFERENCES FoodType(type) ON DELETE CASCADE
            )");
    
            $this->connection->exec("CREATE TABLE IF NOT EXISTS Prefers (
                idUser INTEGER,
                type TEXT,
                PRIMARY KEY (idUser, type),
                FOREIGN KEY (idUser) REFERENCES User(idUser) ON DELETE CASCADE,
                FOREIGN KEY (type) REFERENCES FoodType(type) ON DELETE CASCADE
            )");
    
            $this->connection->exec("CREATE TABLE IF NOT EXISTS Illustrates (
                idPhoto INTEGER,
                idRestau INTEGER,
                PRIMARY KEY (idPhoto, idRestau),
                FOREIGN KEY (idPhoto) REFERENCES Photo(idPhoto) ON DELETE CASCADE,
                FOREIGN KEY (idRestau) REFERENCES Restaurant(idRestau) ON DELETE CASCADE
            )");
    
            $this->connection->exec("CREATE TABLE IF NOT EXISTS Reviewed (
                idUser INTEGER,
                idRestau INTEGER,
                note INTEGER CHECK (note BETWEEN 0 AND 5),
                comment TEXT,
                PRIMARY KEY (idUser, idRestau),
                FOREIGN KEY (idUser) REFERENCES User(idUser) ON DELETE CASCADE,
                FOREIGN KEY (idRestau) REFERENCES Restaurant(idRestau) ON DELETE CASCADE
            )");
    
            $this->connection->exec("CREATE TABLE IF NOT EXISTS Likes (
                idUser INTEGER,
                idRestau INTEGER,
                PRIMARY KEY (idUser, idRestau),
                FOREIGN KEY (idUser) REFERENCES User(idUser) ON DELETE CASCADE,
                FOREIGN KEY (idRestau) REFERENCES Restaurant(idRestau) ON DELETE CASCADE
            )");
    
            $this->connection->commit();
        } catch (\PDOException $e) {
            $this->connection->rollBack();
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
