<?php

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
}