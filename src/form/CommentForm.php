<?php

class CommentForm {

    public function __construct() {}

    public function commentNewForm(Twig\Environment $twig, int $id_post):void {
        echo $twig->render('comment/newComment.html.twig', ['id_post' => $id_post]);
    }
}