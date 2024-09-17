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

    public function showOne(Twig\Environment $twig, int $id, ?int $current_user_id):void {
        $post = $this->post_repository->getPost($id);
        if ($post != null) {
            $user_is_admin = $this->utils->userIsAdmin();
            $author = $this->post_repository->getPostAuthor($post->getIdUser());
            $comments = $this->post_repository->getCommentsByStatus($id, Comment::STATUS_VALIDATED);
            $comments_authors = [];
            if (count($comments) > 0) {
                foreach($comments as $comment) {
                    $comments_authors[] = $this->post_repository->getCommentAuthor($comment->getIdUser());
                }
            }
            
            echo $twig->render('post/post.html.twig', ['post' => $post, 'comments' => $comments, 'current_user_id' => $current_user_id, 'author' => $author, 'comments_authors' => $comments_authors, 'user_is_admin' => $user_is_admin]);
        }
        else {
            echo $twig->render('error.html.twig', []);
        }     
    }

    public function showAll(Twig\Environment $twig):void {
        $posts = $this->post_repository->getPosts();
    
        echo $twig->render('post/posts.html.twig', ['posts' => $posts]);
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
        $data['image'] = $_FILES['image'];
        $post = $this->post_repository->getPost($id);
        foreach($data as $key => $value) {
            if (!$this->utils->compareValue($post, $key, $value)) {
                if ($key == 'image') {
                    $img = $this->utils->uploadFile($value);
                    $post->{'set'.$this->utils->formateKey($key)}($img);
                }
                else {
                    $post->{'set'.$this->utils->formateKey($key)}($value);
                } 
            } 
        }
        $post->setUpdatedAt(new DateTime('now'));
        $this->db_persist->persist($post);

        $this->utils->redirectHome();
    }

    public function adminComments(Twig\Environment $twig, int $id):void {
        $post = $this->post_repository->getPost($id);
        if ($post != null) {
            $comments = $this->post_repository->getCommentsByStatus($id, Comment::STATUS_AWAIT_VALIDATION);
            $comments_authors = [];
            if (count($comments) > 0) {
                foreach($comments as $comment) {
                    $comments_authors[] = $this->post_repository->getCommentAuthor($comment->getIdUser());
                }
            }
            echo $twig->render('comment/commentsAdmin.html.twig', ['post' => $post, 'comments' => $comments, 'comments_authors' => $comments_authors]);
        }
        else {
            echo $twig->render('error.html.twig', []);
        }


    }

    function delete(int $id) {
        $this->post_repository->deletePost($id);

        $this->utils->redirectHome();
    }
}