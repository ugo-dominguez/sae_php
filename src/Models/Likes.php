<?php

class Likes {
    private $idUser;
    private $idRestau;
    
    public function __construct($idUser = null, $idRestau = null) {
        $this->idUser = $idUser;
        $this->idRestau = $idRestau;
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
}