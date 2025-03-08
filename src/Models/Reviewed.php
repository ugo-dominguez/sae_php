<?php
namespace App\Models;

class Reviewed {
    public int $idUser;
    public int $idRestau;
    public int $note;
    public ?string $comment;
    
    public function __construct(array $data) {
        $this->idUser = $data['idUser'];
        $this->idRestau = $data['idRestau'];
        $this->note = $data['note'];
        $this->comment = $data['comment'] ?? null;
    }
}