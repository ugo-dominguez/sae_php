<?php
namespace App\Models;

class Illustrates {
    public int $idRestau;
    public int $idPhoto;
    
    public function __construct(array $data) {
        $this->idRestau = $data['idRestau'];
        $this->idPhoto = $data['idPhoto'];
    }
}