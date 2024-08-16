<?php

require_once 'src/lib/DbConnect.php';
require_once 'src/lib/Utils.php';
require_once 'src/model/Post.php';
require_once 'src/model/Comment.php';

class PostRepository {

    private DbConnect $db_connect;

    private PDO $db;

    private Utils $utils;

    public function __construct() {
        $this->db_connect = new DbConnect();
        $this->db = $this->db_connect->getDb();
        $this->utils = new Utils();
    }

    public function newPost(array $data):void {
        $insert = 'INSERT INTO post (';
        $values = 'VALUES(';
        $execute = [];
        // insert data from $_POST (title, chapo, image, content)
        $i = 0;
        foreach($data as $key => $value) {
            $insert .= $key;
            $values .= '?';
            if ($i < count($data)) {
                $insert .= ', ';
                $values .= ', ';
            }
            $execute[] = $value;
            // $i++;
        }
        // insert automatic data (created_at, updated_at, id_user)
        $insert .= 'created_at, ';
        $values .= 'NOW(), ';

        $insert .= 'updated_at, ';
        $values .= 'NOW(), ';

        $id_user = $this->utils->getIdUser();
        $insert .= 'id_user)';
        $values .= '?)';
        $execute[] = $id_user;

        $query = $this->db->prepare($insert . ' ' . $values);
        var_dump($query);
        $query->execute($execute);
    }
    
    public function getPost(int $id):Post {
        $query = $this->db->prepare('SELECT * FROM post WHERE id= ?');
        $query->execute([$id]);
        $statement = $query->fetchAll(PDO::FETCH_ASSOC);

        $post = new Post($statement[0]);

        return $post;
    }
    
    public function updatePost($id, $title, $chapo, $image, $content, $updated_at):void {
        $query = $this->db->prepare('UPDATE post SET title = ?, chapo = ?, image = ?, content = ?, updated_at = ? WHERE id = ?');
        $query->execute([$title, $chapo, $image, $content, $updated_at->format("Y-m-d H:i:s"), $id]);
    }
    
    public function deletePost($id):void {
        $query = $this->db->prepare('DELETE post WHERE id = ?');
        $query->execute([$id]);
    }

    public function getPosts():array {
        $query = $this->db->prepare('SELECT id, title, chapo, image, content, created_at, updated_at, id_user FROM post');
        $query->execute();
        $statement = $query->fetchAll(PDO::FETCH_ASSOC);
    
        $posts = [];
    
        foreach($statement as $key => $row) {
            $post = new Post($row);
            $posts[] = $post;
        }
    
        return $posts;
    }

    public function getCommentsByStatus(int $id, String $status):array {
        $query = $this->db->prepare('SELECT id, message, status, created_at, validated_at, id_user, id_post FROM comment WHERE id_post = ? AND status = ?');
        $query->execute([$id, $status]);
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