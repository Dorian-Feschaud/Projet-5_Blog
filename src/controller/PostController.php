<?php

require_once 'src/repository/PostRepository.php';
require_once 'src/form/PostForm.php';
require_once 'src/lib/Utils.php';
require_once 'src/lib/DbPersist.php';

class PostController {

    private PostRepository $post_repository;
    private PostForm $post_form;
    private Utils $utils;
    private DbPersist $db_persist;

    public function __construct(){
        $this->post_repository = new PostRepository();
        $this->post_form = new PostForm();
        $this->utils = new Utils();
        $this->db_persist = new DbPersist();
    }

    public function showOne(Twig\Environment $twig, int $id, int $current_user_id):void {
        $post = $this->post_repository->getPost($id);
        $comments = $this->post_repository->getCommentsByStatus($id, Comment::STATUS_VALIDATED);
    
        echo $twig->render('post/post.html.twig', ['post' => $post, 'comments' => $comments, 'current_user_id' => $current_user_id]);
    }

    public function showAll(Twig\Environment $twig, bool $logged_in):void {
        $posts = $this->post_repository->getPosts();
    
        echo $twig->render('post/posts.html.twig', ['posts' => $posts, 'logged_in' => $logged_in]);
    }

    public function showNewForm(Twig\Environment $twig):void {
        $this->post_form->postNewForm($twig);
    }

    public function showEditForm(Twig\Environment $twig, int $id):void {
        $post = $this->post_repository->getPost($id);
        $context = array(
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'chapo' => $post->getChapo(),
            'image' => $post->getImage(),
            'content' => $post->getContent(),
        );
        $this->post_form->postEditForm($twig, $context);
    }

    public function new():void {
        $data = $_POST;
        $data['image'] = $_FILES['image'];
        $this->post_repository->newPost($data);

        $this->utils->redirectHome();
    }

    public function edit(array $data, int $id):void {
        $post = $this->post_repository->getPost($id);
        foreach($data as $key => $value) {
            $post->{'set'.$this->utils->formateKey($key)}($value);
        }
        $this->db_persist->persist($post);

        $this->utils->redirectHome();
    }

    public function adminComments(Twig\Environment $twig, int $id):void {
        $post = $this->post_repository->getPost($id);
        $comments = $this->post_repository->getCommentsByStatus($id, Comment::STATUS_AWAIT_VALIDATION);

        echo $twig->render('comment/commentsAdmin.html.twig', ['post' => $post, 'comments' => $comments]);
    }
}