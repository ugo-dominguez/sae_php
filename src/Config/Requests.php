<?php
namespace App\Config;

use PDO;
use PDOException;

class Requests {
    private PDO $connection;
    
    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }
    
    public function getConnection(): PDO {
        return $this->connection;
    }

    public function getRestaurants(int $number) {
        
    }
}
