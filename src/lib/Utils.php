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

    public function getIdUser():int {
        return $_SESSION['user_id'];
    }

    public function redirectHome():void {
        header("Location: /blog");
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
}