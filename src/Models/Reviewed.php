<?php
namespace App\Models;

use App\Config\Requests;

class Reviewed {
    private $idUser;
    private $idRestau;
    private $note;
    private $comment;
    
    public function __construct($idUser = null, $idRestau = null, $note = null, $comment = null) {
        $this->idUser = $idUser;
        $this->idRestau = $idRestau;
        $this->note = $note;
        $this->comment = $comment;
        $this->restaurant = Requests::getRestaurantById($idRestau);
    }
    
    public function getIdUser() {
        return $this->idUser;
    }
    
    public function setIdUser($idUser) {
        $this->idUser = $idUser;
    }
    
    public function getIdRestau() {
        return $this->idRestau;
    }
    
    public function setIdRestau($idRestau) {
        $this->idRestau = $idRestau;
    }
    
    public function getNote() {
        return $this->note;
    }
    
    public function setNote($note) {
        $this->note = $note;
    }
    
    public function getComment() {
        return $this->comment;
    }
    
    public function setComment($comment) {
        $this->comment = $comment;
    }

    public function getRestaurant() {
        return $this->restaurant;
    }
    
    public function setRestaurant($restaurant) {
        $this->restaurant = $restaurant;
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