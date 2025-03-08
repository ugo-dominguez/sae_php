<?php
namespace App\Models;

class Likes {
    public int $idUser;
    public int $idRestau;
    
    public function __construct(array $data) {
        $this->idUser = $data['idUser'];
        $this->idRestau = $data['idRestau'];
    }
}
