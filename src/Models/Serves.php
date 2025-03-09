<?php
namespace App\Models;

class Serves {
    public int $idRestau;
    public string $type;
    
    public function __construct(array $data) {
        $this->idRestau = $data['idRestau'];
        $this->type = $data['type'];
    }
}