<?php
namespace App\Config;

use PDO;
use PDOException;

class Database {
    public static PDO $connection;
    public static string $dbPath;

    public static function setConnection(string $dbPath = null): void {
        if ($dbPath !== null) {
            self::$dbPath = $dbPath;
        } else {
            self::$dbPath = ROOT_DIR . '/baratie.db';
        }
        try {
            self::$connection = new PDO("sqlite:" . self::$dbPath);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new \Exception("Échec de connexion à la BD : " . $e->getMessage());
        }
    }

    public static function getConnection(): PDO {
        return self::$connection;
    }

    public static function createTables(): void {
        try {
            self::$connection->beginTransaction();
            self::$connection->exec("CREATE TABLE IF NOT EXISTS User (
                idUser INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT NOT NULL,
                password TEXT NOT NULL
            )");
            self::$connection->exec("CREATE TABLE IF NOT EXISTS FoodType (
                type TEXT PRIMARY KEY
            )");
            self::$connection->exec("CREATE TABLE IF NOT EXISTS Restaurant (
                idRestau INTEGER PRIMARY KEY AUTOINCREMENT,
                address TEXT,
                nameR TEXT NOT NULL,
                schedule TEXT,
                website TEXT,
                phone TEXT,
                accessibl INTEGER NOT NULL DEFAULT 0, 
                delivery INTEGER NOT NULL DEFAULT 0
            )");
            self::$connection->exec("CREATE TABLE IF NOT EXISTS Photo (
                idPhoto INTEGER PRIMARY KEY AUTOINCREMENT,
                image TEXT NOT NULL
            )");
            self::$connection->exec("CREATE TABLE IF NOT EXISTS Serves (
                idRestau INTEGER,
                type TEXT,
                PRIMARY KEY (idRestau, type),
                FOREIGN KEY (idRestau) REFERENCES Restaurant(idRestau) ON DELETE CASCADE,
                FOREIGN KEY (type) REFERENCES FoodType(type) ON DELETE CASCADE
            )");
            self::$connection->exec("CREATE TABLE IF NOT EXISTS Prefers (
                idUser INTEGER,
                type TEXT,
                PRIMARY KEY (idUser, type),
                FOREIGN KEY (idUser) REFERENCES User(idUser) ON DELETE CASCADE,
                FOREIGN KEY (type) REFERENCES FoodType(type) ON DELETE CASCADE
            )");
            self::$connection->exec("CREATE TABLE IF NOT EXISTS Illustrates (
                idPhoto INTEGER,
                idRestau INTEGER,
                PRIMARY KEY (idPhoto, idRestau),
                FOREIGN KEY (idPhoto) REFERENCES Photo(idPhoto) ON DELETE CASCADE,
                FOREIGN KEY (idRestau) REFERENCES Restaurant(idRestau) ON DELETE CASCADE
            )");
            self::$connection->exec("CREATE TABLE IF NOT EXISTS Reviewed (
                idUser INTEGER,
                idRestau INTEGER,
                note INTEGER CHECK (note BETWEEN 0 AND 5),
                comment TEXT,
                PRIMARY KEY (idUser, idRestau),
                FOREIGN KEY (idUser) REFERENCES User(idUser) ON DELETE CASCADE,
                FOREIGN KEY (idRestau) REFERENCES Restaurant(idRestau) ON DELETE CASCADE
            )");
            self::$connection->exec("CREATE TABLE IF NOT EXISTS Likes (
                idUser INTEGER,
                idRestau INTEGER,
                PRIMARY KEY (idUser, idRestau),
                FOREIGN KEY (idUser) REFERENCES User(idUser) ON DELETE CASCADE,
                FOREIGN KEY (idRestau) REFERENCES Restaurant(idRestau) ON DELETE CASCADE
            )");
            self::$connection->commit();
        } catch (PDOException $e) {
            self::$connection->rollBack();
            throw new \Exception("Erreur lors de la création des tables: " . $e->getMessage());
        }
    }

    public static function insertRestaurants(array $data): void {
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
            self::insertInto('Restaurant', $restaurantsToInsert);
        } catch (PDOException $e) {
            throw new \Exception("Erreur lors de l'insertion des restaurants: " . $e->getMessage());
        }
    }

    public static function insertInto(string $tableName, array $dataSet): void {
        try {
            $columns = implode(', ', array_keys($dataSet[0]));
            $placeholders = implode(', ', array_fill(0, count($dataSet[0]), '?'));
            $sql = "INSERT INTO {$tableName} ({$columns}) VALUES ({$placeholders})";
            $stmt = self::$connection->prepare($sql);
            self::$connection->beginTransaction();
            foreach ($dataSet as $data) {
                $stmt->execute(array_values($data));
            }
            self::$connection->commit();
        } catch (PDOException $e) {
            self::$connection->rollBack();
            throw new \Exception("Erreur lors de l'insertion dans {$tableName}: " . $e->getMessage());
        }
    }
}
