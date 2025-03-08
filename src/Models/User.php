<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class User {
    public int $id;
    public string $username;
    public string $password;
    
    public function __construct(array $data) {
        $this->id = (int) $data['idUser'];
        $this->username = $data['username'];
        $this->password = $data['password'];
    }
}
