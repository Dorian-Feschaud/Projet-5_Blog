<?php

class DbConnect {

    private PDO $db;

    public function __construct() {
        try {
            $this->db = new PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', '');
        } catch(Exception $e) {
            die('Erreur : '.$e->POSTMessage());
        }
    }

    public function getDb():PDO {
        return $this->db;
    }
}