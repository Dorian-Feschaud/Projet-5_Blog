<?php

require_once 'src/lib/Utils.php';

class ErrorController {

    public function __construct(){}

    public function errorPage(Twig\Environment $twig):void {
        echo $twig->render('error.html.twig', []);   
    }
}