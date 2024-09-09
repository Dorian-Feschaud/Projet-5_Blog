<?php

class PostForm {

    public function __construct() {}

    public function postNewForm(Twig\Environment $twig):void {
        echo $twig->render('post/postNewForm.html.twig', []);
    }

    public function postEditForm(Twig\Environment $twig, array $context):void {   
        echo $twig->render('post/postEditForm.html.twig', $context);
    }
}