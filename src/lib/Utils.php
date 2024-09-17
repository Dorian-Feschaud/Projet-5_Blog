<?php

require_once 'src/lib/TwigLoader.php';
require_once 'src/repository/UserRepository.php';
require_once 'src/model/User.php';

class Utils {

    public function __construct() {}

    public function formateKey(String $key):String {
        $res = '';
        $words = explode('_', $key);
        foreach($words as $word) {
            $res .= ucfirst($word);
        }

        return $res;
    }

    public function formateDate(String $date):\DateTime {
        return new \DateTime($date);
    }

    public function isLoggedIn():bool {
        return (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true);
    }

    public function getIdUser():?int {
        if (isset($_SESSION['user_id'])) {
            return $_SESSION['user_id'];
        }

        return null;
    }

    public function redirectHome():void {
        header("Location: /blog");
        exit();
    }

    public function loginError():void {
        header("Location: /blog/login?error=1");
        exit();
    }

    public function getTwig():\Twig\Environment {
        $twig_loader = new TwigLoader();
        $twig = $twig_loader->getTwig();

        return $twig;
    }

    public function userIsAdmin():bool {
        $user_id = $this->getIdUser();
        $user_repository = new UserRepository();
        $user = $user_repository->getUser($user_id);
        $role = $user->getRole();
        
        return $role == 'admin';
    }

    public function userIsAuthor():bool {
        $user_id = $this->getIdUser();
        $user_repository = new UserRepository();
        $user = $user_repository->getUser($user_id);
        $role = $user->getRole();
        
        return $role == 'author';
    }

    public function uploadFile(array $image):String {
        $target_dir = ROOT_PATH."\uploads\\";
        $res = "uploads/" . basename($image["name"]);
        $target_file = $target_dir . basename($image["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($image["tmp_name"]);
            if($check !== false) {
                // var_dump("File is an image - " . $check["mime"] . ".");
                // die();
                $uploadOk = 1;
            } else {
                // var_dump("File is not an image.");
                // die();
                $uploadOk = 0;
            }
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            // var_dump("Sorry, file already exists.");
            // die();
            $uploadOk = 0;
        }

        // Check file size
        if ($image["size"] > 5000000) {
            // var_dump("Sorry, your file is too large.");
            // die();
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            // var_dump("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
            // die();
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            // var_dump("Sorry, your file was not uploaded.");
            // die();
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($image["tmp_name"], $target_file)) {
                // var_dump("The file ". htmlspecialchars( basename( $image["name"])). " has been uploaded.");
                // die();
            } else {
                // var_dump("Sorry, there was an error uploading your file.");
                // die();
            }
        }

        return $res;
    }

    public function compareValue(mixed $entity, String $key, mixed $value):bool {
        if ($key == 'image') {
            $img = $this->uploadFile($value);
            return $img == $entity->{'get'.$this->formateKey($key)}() || $img == 'uploads/';
        }
        else {
            return $value == $entity->{'get'.$this->formateKey($key)}();
        }
    }
}