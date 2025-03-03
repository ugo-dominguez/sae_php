<?php

class Serves {
    private $idRestau;
    private $type;
    
    public function __construct($idRestau = null, $type = null) {
        $this->idRestau = $idRestau;
        $this->type = $type;
    }
    
    public function getIdRestau() {
        return $this->idRestau;
    }
    
    public function setIdRestau($idRestau) {
        $this->idRestau = $idRestau;
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function setType($type) {
        $this->type = $type;
    }
}