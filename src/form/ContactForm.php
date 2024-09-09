<?php

class ContactForm {

    public function __construct() {}

    public function contactForm(Twig\Environment $twig):void {
        echo $twig->render('contact.html.twig', []);
    }
}