<?php

class User {

    private int $id;
    
    private String $firstname;

    private String $lastname;

    private String $email;

    private String $password;

    private String $image;

    private String $role;

    const ROLE_ADMIN = 'admin';
    const ROLE_AUTHOR = 'author';
    const ROLE_AWAIT_AUTHOR = 'await-author';
    const ROLE_SUBSCRIBER = 'subscriber';

    public function __construct(array $data) {
        $this->setUser($data);
    }

    public function getId():int {
        return $this->id;
    }

    public function setId(int $id):void {
        $this->id = $id;
    }

    public function getFirstname():String {
        return $this->firstname;
    }

    public function setFirstname(String $firstname):void {
        $this->firstname = $firstname;
    }

    public function getLastname():String {
        return $this->lastname;
    }

    public function setLastname(String $lastname):void {
        $this->lastname = $lastname;
    }

    public function getEmail():String {
        return $this->email;
    }

    public function setEmail(String $email):void {
        $this->email = $email;
    }

    public function getPassword():String {
        return $this->password;
    }

    public function setPassword(String $password):void {
        $this->password = $password;
    }

    public function getImage():String {
        return $this->image;
    }

    public function setImage(String $image):void {
        $this->image = $image;
    }

    public function getRole():String {
        return $this->role;
    }

    public function setRole(String $role):void {
        $this->role = $role;
    }

    public function getName():string {
        return get_class($this);
    }

    private function setUser(array $row):void {
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