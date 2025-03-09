<?php
namespace App\Models;

use App\Config\Requests;
use App\Config\Utils;

class Reviewed {
    public $idUser;
    public $idRestau;
    public $note;
    public $comment;
    public $restaurant;

    public function __construct(array $data) {
        $this->idUser = $data['idUser'];
        $this->idRestau = $data['idRestau'];
        $this->note = $data['note'];
        $this->comment = $data['comment'] ?? '';

        $this->restaurant = Requests::getRestaurantById($data['idRestau']);
        $this->author = Requests::getUserById($data['idUser']);
    }
    
    public function getStars() : string {
        return Utils::getStars($this->note);
    }
}