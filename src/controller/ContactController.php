<?php

require_once 'src/form/ContactForm.php';
require_once 'src/lib/Utils.php';

class ContactController {

    private ContactForm $contact_form;

    private Utils $utils;

    public function __construct() {
        $this->contact_form = new ContactForm();
        $this->utils = new Utils();
    }

    public function showContactForm(Twig\Environment $twig) {
        $this->contact_form->contactForm($twig);
    }

    public function sendMail(array $data) {
        $message = '';
        $message .= 'Nom : '.$data['lastname']."\n";
        $message .= 'Prénom : '.$data['firstname']."\n";
        $message .= 'Email : '.$data['email']."\n";
        $message .= 'Message : '.$data['message'];
        ini_set('SMTP','localhost');
        ini_set('smtp_port',1025);
        mail('dorian.feschaud@gmail.com', 'Demande de contact', $message);
        // var_dump(mail('dorian.feschaud@gmail.com', 'Demande de contact', $message));
        // die();
        $this->utils->redirectHome();
    }

}