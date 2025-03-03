<?php

class FoodType {
    private $type;

    public function __construct($type = null) {
        $this->type = $type;
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function setType($type) {
        $this->type = $type;
    }
    
    public function getRestaurants() {
        // Example: SELECT * FROM Restaurant JOIN Serves ON Restaurant.idRestau = Serves.idRestau WHERE Serves.type = $this->type
    }
    
    public function getUsersWhoPrefer() {
        // Example: SELECT * FROM User JOIN Prefers ON User.idUser = Prefers.idUser WHERE Prefers.type = $this->type
    }
}

