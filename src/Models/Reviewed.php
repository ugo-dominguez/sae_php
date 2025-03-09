<?php
namespace App\Models;

use App\Config\Requests;

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
    }
    
    public function getStars() : string {
        $res = '';
        for ($i = 0; $i < $this->note; $i++) {
            $res .= '★';
        }

        if ($this->note - (int) $this->note >= 0.5) {
            $res .= '⯪';
        }

        for ($i = mb_strlen($res); $i < 5; $i++) {
            $res .= '☆';
        }

        return $res;
    }
}