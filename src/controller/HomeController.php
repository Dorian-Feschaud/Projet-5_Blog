<?php

require_once 'src/lib/Utils.php';

class HomeController {

    public function __construct(){}

    public function defaultHomepage(Twig\Environment $twig):void {
        echo $twig->render('defaultHome.html.twig', []);   
    }

    public function loggedInHomepage(Twig\Environment $twig):void {
        $utils = new Utils();
        $user_id = $utils->getIdUser();
        $user_repository = new UserRepository();
        $user = $user_repository->getUser($user_id);
        echo $twig->render('loggedInHome.html.twig', ['user' => $user]);    
    }
}