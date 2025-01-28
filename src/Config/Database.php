<?php
namespace App\Config;

class Database {
    private static $instance = null;
    private \PDO $connection;
    
    private function __construct() {
        $config = [
            'server' => 'servinfo-maria',
            'dbname' => 'DBdominguez',
            'username' => 'dominguez',
            'password' => 'dominguez',
        ];

        try {
            $dsn = "mysql:server={$config['server']};dbname={$config['dbname']}}";
            $this->connection = new \PDO($dsn, $config['username'], $config['password'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false
            ]);
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
}
