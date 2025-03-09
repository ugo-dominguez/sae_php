<?php

namespace App\Models;

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
