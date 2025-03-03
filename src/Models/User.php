<?php

class User {
    private $idUser;
    private $username;
    private $password;
    
    public function __construct($idUser = null, $username = null, $password = null) {
        $this->idUser = $idUser;
        $this->username = $username;
        $this->password = $password;
    }
    
    public function getIdUser() {
        return $this->idUser;
    }
    
    public function setIdUser($idUser) {
        $this->idUser = $idUser;
    }
    
    public function getUsername() {
        return $this->username;
    }
    
    public function setUsername($username) {
        $this->username = $username;
    }
    
    public function getPassword() {
        return $this->password;
    }
    
    public function setPassword($password) {
        $this->password = $password;
    }
    
    public function getPreferredFoodTypes() {
        // Example: SELECT * FROM FoodType JOIN Prefers ON FoodType.type = Prefers.type WHERE Prefers.idUser = $this->idUser
    }
    
    public function getReviews() {
        // Example: SELECT * FROM Reviewed WHERE idUser = $this->idUser
    }
    
    public function getLikedRestaurants() {
        // Example: SELECT * FROM Restaurant JOIN Likes ON Restaurant.idRestau = Likes.idRestau WHERE Likes.idUser = $this->idUser
    }
}