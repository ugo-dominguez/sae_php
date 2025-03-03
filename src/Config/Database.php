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

    public function getRestaurants() {
        try {
            $stmt = $this->connection->prepare("
                SELECT * from RESTAURANT
            ");
            printf($stmt);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors du chargement des restaurants: " . $e->getMessage());
        }
    }
}
