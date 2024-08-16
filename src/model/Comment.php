<?php

require_once 'src/lib/Utils.php';

class Comment {

    const STATUS_VALIDATED = 'validated';
    const STATUS_AWAIT_VALIDATION = 'await-validation';
    const STATUS_REFUSED = 'refused';

    private int $id;
    
    private String $message;

    private String $status;

    private \DateTime $created_at;

    private ?\DateTime $validated_at;

    private int $id_user;

    private int $id_post;

    public function __construct(array $data) {
        $this->setComment($data);
    }

    public function getId():int {
        return $this->id;
    }

    public function setId(int $id):void {
        $this->id = $id;
    }

    public function getMessage():String {
        return $this->message;
    }

    public function setMessage(String $message):void {
        $this->message = $message;
    }

    public function getStatus():String {
        return $this->status;
    }

    public function setStatus(String $status):void {
        $this->status = $status;
    }

    public function getCreatedAt():\DateTime {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at):void {
        $this->created_at = $created_at;
    }

    public function getValidatedAt():?\DateTime {
        return $this->validated_at;
    }

    public function setValidatedAt(?\DateTime $validated_at):void {
        $this->validated_at = $validated_at;
    }

    public function getIdUser():int {
        return $this->id_user;
    }

    public function setIdUser(int $id_user):void {
        $this->id_user = $id_user;
    }

    public function getIdPost():int {
        return $this->id_post;
    }

    public function setIdPost(int $id_post):void {
        $this->id_post = $id_post;
    }

    public function getName():string {
        return get_class($this);
    }

    private function setComment(array $row):void {
        $utils = new Utils();
        foreach($row as $key => $value) {
            if (str_contains($key, '_at') && $value != null) {
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