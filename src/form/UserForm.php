<?php

class UserForm {

    public function __construct() {}

    public function loginForm(Twig\Environment $twig):void {
        echo $twig->render('userLogin.html.twig', []);
    }

    public function registerForm(Twig\Environment $twig):void {
        echo $twig->render('userRegister.html.twig', []);
    }
}