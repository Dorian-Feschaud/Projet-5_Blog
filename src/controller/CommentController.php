<?php

require_once 'src/repository/CommentRepository.php';
require_once 'src/form/CommentForm.php';
require_once 'src/lib/Utils.php';
require_once 'src/lib/DbPersist.php';

class CommentController {

    private CommentRepository $comment_repository;
    private CommentForm $comment_form;
    private Utils $utils;
    private DbPersist $db_persist;

    public function __construct(){
        $this->comment_repository = new CommentRepository();
        $this->comment_form = new CommentForm();
        $this->utils = new Utils();
        $this->db_persist = new DbPersist();
    }

    public function showOne(Twig\Environment $twig, int $id):void {
        $comment = $this->comment_repository->getComment($id);
    
        echo $twig->render('comment/comment.html.twig', ['comment' => $comment]);
    }

    public function showAll(Twig\Environment $twig):void {
        $comments = $this->comment_repository->getComments();
    
        echo $twig->render('comment/comments.html.twig', ['comments' => $comments]);
    }

    public function showNewForm(Twig\Environment $twig, int $id_post):void {
        $this->comment_form->commentNewForm($twig, $id_post);
    }

    public function new(array $data, int $id_post):void {
        $this->comment_repository->newComment($data, $id_post);

        $this->utils->redirectHome();
    }

    public function valid_comment(int $id):void {
        $comment = $this->comment_repository->getComment($id);
        $comment->setStatus(Comment::STATUS_VALIDATED);
        $comment->setValidatedAt(new \DateTime());
        $this->db_persist->persist($comment);
        
        $this->utils->redirectHome();
    }

    public function refuse_comment(int $id):void {
        $comment = $this->comment_repository->getComment($id);
        $comment->setStatus(Comment::STATUS_REFUSED);
        $this->db_persist->persist($comment);
        
        $this->utils->redirectHome();
    }
}