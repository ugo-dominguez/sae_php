<?php

class Restaurant {
    private $idRestau;
    private $address;
    private $nameR;
    private $schedule;
    private $website;
    private $phone;
    private $accessible;
    private $delivery;
    
    public function __construct($idRestau = null, $address = null, $nameR = null, $schedule = null, 
                               $website = null, $phone = null, $accessible = false, $delivery = false) {
        $this->idRestau = $idRestau;
        $this->address = $address;
        $this->nameR = $nameR;
        $this->schedule = $schedule;
        $this->website = $website;
        $this->phone = $phone;
        $this->accessible = $accessible;
        $this->delivery = $delivery;
    }
    
    public function getIdRestau() {
        return $this->idRestau;
    }
    
    public function setIdRestau($idRestau) {
        $this->idRestau = $idRestau;
    }
    
    public function getAddress() {
        return $this->address;
    }
    
    public function setAddress($address) {
        $this->address = $address;
    }
    
    public function getNameR() {
        return $this->nameR;
    }
    
    public function setNameR($nameR) {
        $this->nameR = $nameR;
    }
    
    public function getSchedule() {
        return $this->schedule;
    }
    
    public function setSchedule($schedule) {
        $this->schedule = $schedule;
    }
    
    public function getWebsite() {
        return $this->website;
    }
    
    public function setWebsite($website) {
        $this->website = $website;
    }
    
    public function getPhone() {
        return $this->phone;
    }
    
    public function setPhone($phone) {
        $this->phone = $phone;
    }
    
    public function isAccessible() {
        return $this->accessible;
    }
    
    public function setAccessible($accessible) {
        $this->accessible = $accessible;
    }
    
    public function hasDelivery() {
        return $this->delivery;
    }
    
    public function setDelivery($delivery) {
        $this->delivery = $delivery;
    }
    
    public function getFoodTypes() {
        // Example: SELECT * FROM FoodType JOIN Serves ON FoodType.type = Serves.type WHERE Serves.idRestau = $this->idRestau
    }
    
    public function getPhotos() {
        // Example: SELECT * FROM Photo JOIN Illustrates ON Photo.idPhoto = Illustrates.idPhoto WHERE Illustrates.idRestau = $this->idRestau
    }
    
    public function getReviews() {
        // Example: SELECT * FROM Reviewed WHERE idRestau = $this->idRestau
    }
}