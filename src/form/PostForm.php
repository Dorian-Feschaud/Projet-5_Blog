<?php

class PostForm {

    public function __construct() {}

    public function postNewForm(Twig\Environment $twig):void {
        echo $twig->render('postNewForm.html.twig', []);
    }

    public function postEditForm(Twig\Environment $twig, array $context):void {   
        echo $twig->render('postEditForm.html.twig', $context);
    }
}