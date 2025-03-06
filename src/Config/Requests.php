<?php
namespace App\Config;

use PDO;
use PDOException;

use App\Config\Database;

class Requests {
    private static PDO $connection;

    public static function setConnexion(): void {
        self::$connection = Database::$connection;
    }
}
