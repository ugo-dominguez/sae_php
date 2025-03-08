<?php
namespace App\Models;

class Prefers {
    public int $idUser;
    public string $type;
    
    public function __construct(array $data) {
        $this->idUser = $data['idUser'];
        $this->type = $data['type'];
    }
}