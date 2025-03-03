<?php

class Photo {
    private $idPhoto;
    private $image;
    
    public function __construct($idPhoto = null, $image = null) {
        $this->idPhoto = $idPhoto;
        $this->image = $image;
    }
    
    public function getIdPhoto() {
        return $this->idPhoto;
    }
    
    public function setIdPhoto($idPhoto) {
        $this->idPhoto = $idPhoto;
    }
    
    public function getImage() {
        return $this->image;
    }
    
    public function setImage($image) {
        $this->image = $image;
    }
    
    public function getRestaurant() {
        // Example: SELECT * FROM Restaurant JOIN Illustrates ON Restaurant.idRestau = Illustrates.idRestau WHERE Illustrates.idPhoto = $this->idPhoto
    }
}