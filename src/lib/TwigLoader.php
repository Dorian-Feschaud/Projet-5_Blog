<?php

require_once 'vendor/autoload.php';

class TwigLoader {

    private \Twig\Loader\FilesystemLoader $loader;

    private \Twig\Environment $twig;

    public function __construct() {
        $this->loader = new \Twig\Loader\FilesystemLoader('templates');
        $this->twig = new \Twig\Environment($this->loader);
    }

    public function getTwig():Twig\Environment {
        return $this->twig;
    }
}