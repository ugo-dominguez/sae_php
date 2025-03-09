<?php
namespace App\Config;

use PDO;
use PDOException;

class Database {
    public static string $dbPath = ROOT_DIR . '/baratie.db';
    public static ?PDO $connection = null;
    public static $data = null;
    
    public static function getConnection(): PDO {
        if (self::$connection === null) {
            try {
                self::$connection = new PDO("sqlite:" . self::$dbPath);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::initDatabase();
            } catch (PDOException $e) {
                throw new \Exception("Échec de connexion à la BD : " . $e->getMessage());
            }
        }
        return self::$connection;
    }

    public static function initDatabase(): void {
        if (!self::isDatabaseInitialized()) {
            $jsonFilePath = ROOT_DIR . "/public/assets/restaurants_orleans.json";
            $provider = new Provider($jsonFilePath);
            self::$data = $provider->getData();
            self::createTables();
            self::insertInitData();
        } else {
            self::createTables();
        }
    }

    public static function isDatabaseInitialized(): bool {
        try {
            $stmt = self::$connection->query("SELECT COUNT(*) FROM Restaurant");
            return (int)$stmt->fetchColumn() > 0;

        } catch (PDOException $e) {
            return false;
        }
    }

    public static function insertInitData() {
        try {
            $foodTypes = self::prepareFoodTypeData();
            self::insertInto('FoodType', $foodTypes);
    
            $restaurants = self::prepareRestaurantData();
            self::insertInto('Restaurant', $restaurants);
    
            $servesData = self::prepareServesData();
            self::insertInto('Serves', $servesData);

        } catch (PDOException $e) {
            self::$connection->rollBack();
            throw new \Exception("Erreur lors de l'insertion des données initiales: " . $e->getMessage());
        }
    }

    public static function insertUser(string $username, string $hashedPassword) {
        try {
            $userData[] = [
                'username' => $username,
                'password' => $hashedPassword
            ];
            self::insertInto('User', $userData);
        } catch (PDOException $e) {
            self::$connection->rollBack();
            throw new \Exception("Erreur lors de l'insertion d'un nouvel utilisateur: " . $e->getMessage());
        }
    }

    private static function prepareFoodTypeData(): array {
        $foodTypes = [];
        foreach (self::$data as $restaurant) {
            $cuisines = is_array($restaurant['cuisine']) ? $restaurant['cuisine'] : [$restaurant['cuisine']];
            $foodTypes = array_merge($foodTypes, $cuisines);
        }
        return array_map(fn($cuisine) => ['type' => $cuisine], array_unique($foodTypes));
    }

    private static function prepareRestaurantData(): array {
        return array_map(fn($restaurant) => [
            'nameR' => $restaurant['name'] ?? null, 
            'city' => $restaurant['commune'] ?? null,
            'schedule' => $restaurant['opening_hours'] ?? null,
            'website' => $restaurant['website'] ?? null,
            'phone' => $restaurant['phone'] ?? null,
            'typeR' => $restaurant['type'] ?? null,
            'latitude' => $restaurant['geo_point_2d']['lat'] ?? null,
            'longitude' => $restaurant['geo_point_2d']['lon'] ?? null,
            'accessibl' => ($restaurant['wheelchair'] === 'yes') ? 1 : 0,
            'delivery' => ($restaurant['delivery'] === 'yes') ? 1 : 0,
        ], self::$data);
    }

    private static function prepareServesData(): array {
        $servesData = [];
        foreach (self::$data as $index => $restaurant) {
            $idRestau = $index + 1;
            $cuisines = is_array($restaurant['cuisine']) ? $restaurant['cuisine'] : [$restaurant['cuisine']];
            
            foreach ($cuisines as $cuisine) {
                $servesData[] = [
                    'idRestau' => $idRestau,
                    'type' => $cuisine
                ];
            }
        }
        return $servesData;
    }

    public static function insertInto(string $tableName, array $dataSet) {
        try {
            if (empty($dataSet)) {
                return;
            }

            $columns = implode(', ', array_keys($dataSet[0]));
            $placeholders = implode(', ', array_fill(0, count($dataSet[0]), '?'));
            $sql = "INSERT INTO {$tableName} ({$columns}) VALUES ({$placeholders})";

            self::$connection->beginTransaction();
            $stmt = self::$connection->prepare($sql);
            foreach ($dataSet as $data) {
                $stmt->execute(array_values($data));
            }
            self::$connection->commit();
            
        } catch (PDOException $e) {
            self::$connection->rollBack();
            throw new \Exception("Erreur lors de l'insertion dans {$tableName}: " . $e->getMessage());
        }
    }
    
    public static function createTables() {
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
                city TEXT,
                nameR TEXT NOT NULL,
                schedule TEXT,
                website TEXT,
                phone TEXT,
                typeR TEXT,
                latitude REAL,
                longitude REAL,
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
    
    public static function deleteTables() {
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
            self::$connection->exec($sql);

        } catch (PDOException $e) {
            self::$connection->rollBack();
            throw new \Exception("Erreur lors de l'insertion dans {$tableName}: " . $e->getMessage());
        }
    }
}
