<?php

require_once 'src/lib/Utils.php';

class Post {

    private int $id;

    private String $title;

    private String $chapo;

    private String $image;

    private String $content;

    private Datetime $created_at;

    private Datetime $updated_at;

    private int $id_user;

    public function __construct(array $data) {
        $this->setPost($data);
    }

    public function getId():int {
        return $this->id;
    }

    public function setId($id):void {
        $this->id = $id;
    }

    public function getTitle():String {
        return $this->title;
    }

    public function setTitle($title):void {
        $this->title = $title;
    }

    public function getChapo():String {
        return $this->chapo;
    }

    public function setChapo($chapo):void {
        $this->chapo = $chapo;
    }

    public function getImage():String {
        return $this->image;
    }

    public function setImage($image):void {
        $this->image = $image;
    }

    public function getContent():String {
        return $this->content;
    }

    public function setContent($content):void {
        $this->content = $content;
    }

    public function getCreatedAt():\DateTime {
        return $this->created_at;
    }

    public function setCreatedAt($created_at):void {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt():\DateTime {
        return $this->updated_at;
    }

    public function setUpdatedAt($updated_at):void {
        $this->updated_at = $updated_at;
    }

    public function getIdUser():int {
        return $this->id_user;
    }

    public function setIdUser($id_user):void {
        $this->id_user = $id_user;
    }

    public function getName():string {
        return get_class($this);
    }

    private function setPost(array $row):void {
        $utils = new Utils();
        foreach($row as $key => $value) {
            if (str_contains($key, '_at')) {
                $this->{'set'.$utils->formateKey($key)}($utils->formateDate($value));
            }
            else {
                $this->{'set'.$utils->formateKey($key)}($value);
            }
        }
    }

    public function getAll():array {
        return get_object_vars($this);
    }
    
}