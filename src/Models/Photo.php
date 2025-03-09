<?php
namespace App\Models;

class Photo {
    public int $idPhoto;
    public string $image;
    
    public function __construct(array $data) {
        $this->idPhoto = $data['idPhoto'];
        $this->image = $data['image'];
    }
}