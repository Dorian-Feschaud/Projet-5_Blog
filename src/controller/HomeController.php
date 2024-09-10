<?php

require_once 'src/lib/Utils.php';

class HomeController {

    public function __construct(){}

    public function homepage(Twig\Environment $twig):void {
        echo $twig->render('home.html.twig', []);   
    }
}