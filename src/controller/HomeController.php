<?php

require_once 'src/lib/Utils.php';

class HomeController {

    public function __construct(){}

    public function homepage(Twig\Environment $twig, bool $logged_in):void {
        $user = null;
        if ($logged_in) {
            $utils = new Utils();
            $user_id = $utils->getIdUser();
            $user_repository = new UserRepository();
            $user = $user_repository->getUser($user_id);
        }
        echo $twig->render('home.html.twig', ['logged_in' => $logged_in, 'user' => $user]);   
    }
}