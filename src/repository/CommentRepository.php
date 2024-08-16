<?php

require_once 'src/lib/DbConnect.php';
require_once 'src/lib/Utils.php';
require_once 'src/model/Comment.php';

class CommentRepository {

    private DbConnect $db_connect;

    private PDO $db;

    private Utils $utils;

    public function __construct() {
        $this->db_connect = new DbConnect();
        $this->db = $this->db_connect->getDb();
        $this->utils = new Utils();
    }

    public function newComment(array $data, int $id_post):void {
        $insert = 'INSERT INTO comment(';
        $values = 'VALUES(';
        $execute = [];
        foreach($data as $key => $value) {
            $insert .= $key . ', ';
            $values .= '?, ';
            $execute[] = $value;
            // if (str_contains($key, '_at')) {
            //     $values .= 'NOW(), ';
            // }
            // else {
            //     $values .= '?, ';
            //     $execute[] = $value;
            // }
        }
        $insert .= 'status, ';
        $values .= '?, ';
        $execute[] = Comment::STATUS_AWAIT_VALIDATION;
        $insert .= 'created_at, ';
        $values .= 'NOW(), ';
        $insert .= 'validated_at, '; // champ à revoir
        $values .= '?, ';
        $execute[] = null;
        $insert .= 'id_post, ';
        $values .= '?, ';
        $execute[] = $id_post;
        $id_user = $this->utils->getIdUser();
        $insert .= 'id_user, ';
        $values .= '?, ';
        $execute[] = $id_user;
        $insert = substr($insert, 0, -2);
        $insert .= ')';
        $values = substr($values, 0, -2);
        $values .= ')';

        $query = $this->db->prepare($insert . ' ' . $values);
        $query->execute($execute);
    }
    
    public function getComment(int $id):Comment {
        $query = $this->db->prepare('SELECT * FROM comment WHERE id= ?');
        $query->execute([$id]);
        $statement = $query->fetchAll(PDO::FETCH_ASSOC);

        $post = new Comment($statement[0]);

        return $post;
    }
    
    // public function updatePost($id, $title, $chapo, $image, $content, $updated_at):void {
    //     $query = $this->db->prepare('UPDATE post SET title = ?, chapo = ?, image = ?, content = ?, updated_at = ? WHERE id = ?');
    //     $query->execute([$title, $chapo, $image, $content, $updated_at->format("Y-m-d H:i:s"), $id]);
    // }
    
    // public function deletePost($id):void {
    //     $query = $this->db->prepare('DELETE post WHERE id = ?');
    //     $query->execute([$id]);
    // }

    public function getComments():array {
        $query = $this->db->prepare('SELECT id, message, status, created_at, validated_at, id_user, id_post FROM comment');
        $query->execute();
        $statement = $query->fetchAll(PDO::FETCH_ASSOC);
    
        $comments = [];
    
        foreach($statement as $key => $row) {
            $comment = new Comment($row);
            $comments[] = $comment;
        }
    
        return $comments;
    }

    // private function execute
}