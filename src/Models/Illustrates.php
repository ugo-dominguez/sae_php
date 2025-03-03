<?php

class Illustrates {
    private $idRestau;
    private $idPhoto;
    
    public function __construct($idRestau = null, $idPhoto = null) {
        $this->idRestau = $idRestau;
        $this->idPhoto = $idPhoto;
    }
    
    public function getIdRestau() {
        return $this->idRestau;
    }
    
    public function setIdRestau($idRestau) {
        $this->idRestau = $idRestau;
    }
    
    public function getIdPhoto() {
        return $this->idPhoto;
    }
    
    public function setIdPhoto($idPhoto) {
        $this->idPhoto = $idPhoto;
    }
}