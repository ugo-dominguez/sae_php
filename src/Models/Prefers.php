<?php

class Prefers {
    private $idUser;
    private $type;
    
    public function __construct($idUser = null, $type = null) {
        $this->idUser = $idUser;
        $this->type = $type;
    }
    
    public function getIdUser() {
        return $this->idUser;
    }
    
    public function setIdUser($idUser) {
        $this->idUser = $idUser;
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function setType($type) {
        $this->type = $type;
    }
}