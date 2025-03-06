<?php
namespace App\Config;

use PDO;
use PDOException;

use App\Config\Database;

class Requests {
    private static PDO $connection = Database::$connection;
}
