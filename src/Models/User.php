<?php
namespace App\Models;

use App\Config\Database;
use PDO;

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
        // Exemple: SELECT * FROM FoodType JOIN Prefers ON FoodType.type = Prefers.type WHERE Prefers.idUser = $this->idUser
    }
    
    public function getReviews() {
        // Exemple: SELECT * FROM Reviewed WHERE idUser = $this->idUser
    }
    
    public function getLikedRestaurants() {
        // Exemple: SELECT * FROM Restaurant JOIN Likes ON Restaurant.idRestau = Likes.idRestau WHERE Likes.idUser = $this->idUser
    }

    /**
     * @param string $username
     * @return User|false
     */
    public static function findByUsername($username) {
        $db = Database::getConnection();
    
        error_log("Nombre d'utilisateurs en base: " . $db->query("SELECT COUNT(*) FROM User")->fetchColumn());
    
        $stmt = $db->prepare("SELECT * FROM User WHERE username = :username");
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
    
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row = $stmt->fetch();
    
        error_log("Recherche de '$username' => " . print_r($row, true));
    
        if (!$row) {
            return false;
        }
        
        $user = new User($row['idUser'], $row['username'], $row['password']);
        return $user;
    }
    

    /**
     * @param string $username
     * @param string $hashedPassword
     * @return User
     */
    public static function createUser($username, $hashedPassword) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO user (username, password) VALUES (:username, :password)");
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->execute();

        $newId = $db->lastInsertId();
        return new User($newId, $username, $hashedPassword);
    }
}
