<?php

require_once 'src/controller/HomeController.php';
require_once 'src/controller/PostController.php';
require_once 'src/controller/CommentController.php';
require_once 'src/controller/UserController.php';
require_once 'src/controller/ContactController.php';
require_once 'src/form/PostForm.php';
require_once 'src/form/CommentForm.php';
require_once 'src/form/UserForm.php';
require_once 'src/form/UserForm.php';
require_once 'src/lib/Utils.php';

class RouteController {

    private Twig\Environment $twig;

    private Utils $utils;

    private bool $logged_in;

    public function __construct(){
        $this->utils = new Utils();
        $this->twig = $this->utils->getTwig();
        $this->logged_in = $this->utils->isLoggedIn();
    }

    function homepage():void {
        $home_controller = new HomeController();
        $home_controller->homepage($this->twig);
    }

    function register():void {
        if ($this->logged_in) {
            $this->utils->redirectHome();
        }
        else {
            if (!empty($_POST)) {
                $user_controller = new UserController();
                $user_controller->register();
            }
            else {
                $user_form = new UserForm();
                $user_form->registerForm($this->twig);
            }
        }
    }

    function login():void {
        if ($this->logged_in) {
            $this->utils->redirectHome();
        }
        else {
            if (!empty($_POST)) {
                $user_controller = new UserController();
                $user_controller->login($_POST);
            }
            else {
                $user_form = new UserForm();
                $user_form->loginForm($this->twig);
            }
        }
    }

    function logout():void {
        if (!$this->logged_in) {
            $this->utils->redirectHome();
        }
        else {
            $user_controller = new UserController();
            $user_controller->logout();
        }
    }

    function showAll(Object $controller):void {
        if (is_a($controller, 'UserController')) {
            if ($this->utils->userIsAdmin()) {
                if (!empty($_GET)) {
                    $controller->showAllByRole($this->twig, $_GET['role']);
                }
                else {
                    $controller->showAll($this->twig);
                }
            }
        }
        else {
            $controller->showAll($this->twig);
        }
    }

    function showOne(Object $controller, int $id):void {
        $current_user_id = $this->utils->getIdUser();
        if ($this->logged_in && $id == $current_user_id && get_class($controller) == 'UserController')  {
            $controller->profil($this->twig, $id, $current_user_id);
        }
        else {
            $controller->showOne($this->twig, $id, $current_user_id);
        }
    }

    function action(Object $controller):void {
        if ($this->logged_in) {
            if (!empty($_POST)) {
                $controller->new();
            }
            else {
                $controller->showNewForm($this->twig);
            }
        }
        else {
            $this->utils->redirectHome();
        }
        
    }

    function actionChildren(Object $controller, int $id_parent):void {
        if ($this->logged_in) {
            if (!empty($_POST)) {
                $controller->new($_POST, $id_parent);
            }
            else {
                $controller->showNewForm($this->twig, $id_parent);
            }
        }
        else {
            $this->utils->redirectHome();
        }
    }

    function adminComments(Object $controller, int $id_parent):void {
        $id_current_user = $this->utils->getIdUser();
        $post_repository = new PostRepository();
        $post = $post_repository->getPost($id_parent);
        $id_author = $post->getIdUser();
        if ($this->logged_in && ($this->utils->userIsAdmin() || $id_current_user == $id_author)) {
            $controller->adminComments($this->twig, $id_parent);
        }
        else {
            $this->utils->redirectHome();
        }
    }

    function author_submission(Object $controller, int $id):void {
        $controller->author_submission($id);
    }

    function valid_author(Object $controller, int $id):void {
        $controller->valid_author($id);
    }

    function refuse_author(Object $controller, int $id):void {
        $controller->refuse_author($id);
    }

    function edit(Object $controller, int $id):void {
        if ($this->logged_in) {
            if (!empty($_POST)) {
                $controller->edit($_POST, $id);
            }
            else {
                $controller->showEditForm($this->twig, $id);
            }
        }
        else {
            $this->utils->redirectHome();
        }
    }

    function valid_comment(Object $controller, int $id):void {
        $controller->valid_comment($id);
    }

    function refuse_comment(Object $controller, int $id):void {
        $controller->refuse_comment($id);
    }

    function posts(Object $controller, int $id):void {
        if ($this->logged_in && ($this->utils->userIsAdmin() || $this->utils->userIsAuthor())) {
            $controller->posts($this->twig, $id);
        }
        else {
            $this->utils->redirectHome();
        }
    }

    function contact():void {
        $contact_controller = new ContactController();
        if (!empty($_POST)) {
            $contact_controller->sendMail($_POST);
        }
        else {
            $contact_controller->showContactForm($this->twig);
        }
    }
}

