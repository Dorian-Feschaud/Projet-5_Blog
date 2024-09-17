<?php

class UserForm {

    public function __construct() {}

    public function loginForm(Twig\Environment $twig, array $data):void {
        if (isset($data['error'])) {
            $error = true;
        }
        else {
            $error = false;
        }
        echo $twig->render('user/userLogin.html.twig', ['error' => $error]);
    }

    public function registerForm(Twig\Environment $twig):void {
        echo $twig->render('user/userRegister.html.twig', []);
    }

    public function userEditForm(Twig\Environment $twig, array $context):void {   
        echo $twig->render('user/userEditForm.html.twig', $context);
    }
}