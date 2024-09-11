<?php

require_once 'src/lib/DbConnect.php';
require_once 'src/lib/Utils.php';
require_once 'src/model/User.php';

class UserRepository {

    private DbConnect $db_connect;

    private PDO $db;

    private Utils $utils;

    public function __construct() {
        $this->db_connect = new DbConnect();
        $this->db = $this->db_connect->getDb();
        $this->utils = new Utils();
    }

    public function register(array $data):void {
        $insert = 'INSERT INTO user(';
        $values = 'VALUES(';
        $execute = [];
        // foreach($data as $key => $value) {
        //     $insert .= $key . ', ';
        //     if (str_contains($key, '_at')) {
        //         $values .= 'NOW(), ';
        //     }
        //     else if ($key == 'password') {
        //         $values .= '?, ';
        //         $execute[] = hash('sha256', $value);
        //     }
        //     else {
        //         $values .= '?, ';
        //         $execute[] = $value;
        //     }
        // }
        foreach($data as $key => $value) {
            $insert .= $key . ', ';
            $values .= '?, ';
            if ($key == 'password') {
                $execute[] = hash('sha256', $value);
            }
            else if ($key == 'image') {
                $file = $this->utils->uploadFile($value);
                $execute[] = $file;
            }
            else {
                $execute[] = $value;
            }
        }

        // $insert .= 'created_at, ';
        // $values .= 'NOW(), ';

        $insert .= 'role, ';
        $values .= '?, ';
        $execute[] = User::ROLE_SUBSCRIBER;

        $insert = substr($insert, 0, -2);
        $insert .= ')';
        $values = substr($values, 0, -2);
        $values .= ')';

        $query = $this->db->prepare($insert . ' ' . $values);
        $query->execute($execute);
    }

    
    public function login(array $data):int {
        $query = $this->db->prepare('SELECT id, password from user WHERE email = ? AND password = ? LIMIT 1');
        $query->execute([$data['email'], hash('sha256', $data['password'])]);
        $statement = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($statement) == 1) {
            return $statement[0]['id'];
        }
        
        return 0;
    }
    
    public function getUser(int $id):?User {
        $query = $this->db->prepare('SELECT * FROM user WHERE id= ?');
        $query->execute([$id]);
        $statement = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($statement)) {
            $user = new User($statement[0]);
            return $user;
        }

        return null;

    }

    public function getUsers():array {
        $query = $this->db->prepare('SELECT *  FROM user');
        $query->execute();
        $statement = $query->fetchAll(PDO::FETCH_ASSOC);
    
        $users = [];
    
        foreach($statement as $key => $row) {
            $user = new User($row);
            $users[] = $user;
        }
    
        return $users;
    }

    public function getUsersByRole(string $role):array {
        $query = $this->db->prepare('SELECT *  FROM user WHERE role= ?');
        $query->execute([$role]);
        $statement = $query->fetchAll(PDO::FETCH_ASSOC);
    
        $users = [];
    
        foreach($statement as $key => $row) {
            $user = new User($row);
            $users[] = $user;
        }
    
        return $users;
    }

    public function getPosts(int $id):array {
        $query = $this->db->prepare('SELECT id, title, chapo, image, content, created_at, updated_at, id_user FROM post WHERE id_user = ?');
        $query->execute([$id]);
        $statement = $query->fetchAll(PDO::FETCH_ASSOC);
    
        $posts = [];
    
        foreach($statement as $key => $row) {
            $post = new Post($row);
            $posts[] = $post;
        }
    
        return $posts;
    }
    
}