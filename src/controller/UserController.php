<?php

require_once 'src/repository/UserRepository.php';
require_once 'src/form/UserForm.php';
require_once 'src/lib/Utils.php';
require_once 'src/lib/DbPersist.php';

class UserController {
    
    private UserRepository $user_repository;

    private UserForm $user_form;
    
    private Utils $utils;
    
    private DbPersist $db_persist;
    
    public function __construct(){
        $this->user_repository = new UserRepository();
        $this->user_form = new UserForm();
        $this->utils = new Utils();
        $this->db_persist = new DbPersist();
    }
    
    public function login(array $data):void {
        $user_id = $this->user_repository->login($data);
        switch ($user_id) {
            case 0: // login error
                $this->utils->loginError();
                break;
                default: // login successful
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user_id;
                break;
            }
            
            $this->utils->redirectHome();
        }
        
    public function register():void {
        $data = $_POST;
        $data['image'] = $_FILES['image'];
        $this->user_repository->register($data);
        
        $this->utils->redirectHome();
    }
    
    public function logout():void {
        session_destroy();
        $this->utils->redirectHome();
    }

    public function profil(Twig\Environment $twig, int $id):void {
        $user = $this->user_repository->getUser($id);
        
        echo $twig->render('user/profil.html.twig', ['user' => $user]);
    }
    
    public function showOne(Twig\Environment $twig, int $id):void {
        $user = $this->user_repository->getUser($id);

        if ($user != null) {
            echo $twig->render('user/user.html.twig', ['user' => $user]);
        }
        else {
            echo $twig->render('error.html.twig', ['user' => $user]);
        }
        
    }
    
    public function author_submission(int $id):void {
        $user = $this->user_repository->getUser($id);
        $user->setRole(User::ROLE_AWAIT_AUTHOR);
        $this->db_persist->persist($user);
        
        $this->utils->redirectHome();
    }

    public function valid_author(int $id):void {
        $user = $this->user_repository->getUser($id);
        $user->setRole(User::ROLE_AUTHOR);
        $this->db_persist->persist($user);
        
        $this->utils->redirectHome();
    }

    public function refuse_author(int $id):void {
        $user = $this->user_repository->getUser($id);
        $user->setRole(User::ROLE_SUBSCRIBER);
        $this->db_persist->persist($user);
        
        $this->utils->redirectHome();
    }
    
    public function showAll(Twig\Environment $twig):void {
        $users = $this->user_repository->getUsers();
        
        echo $twig->render('user/users.html.twig', ['users' => $users]);
    }
    
    public function showAllByRole(Twig\Environment $twig, string $role):void {
        $users = $this->user_repository->getUsersByRole($role);
        
        echo $twig->render('user/users.html.twig', ['users' => $users]);
    }

    public function posts(Twig\Environment $twig, int $id):void {
        $posts = $this->user_repository->getPosts($id);

        echo $twig->render('post/posts.html.twig', ['posts' => $posts]);
    }

    public function showEditForm(Twig\Environment $twig, int $id):void {
        $user = $this->user_repository->getUser($id);
        $context = array(
            'id' => $user->getId(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'email' => $user->getEmail(),
            'image' => $user->getImage(),
            'password' => $user->getPassword(),
        );
        $this->user_form->userEditForm($twig, $context);
    }

    public function edit(array $data, int $id):void {
        $data['image'] = $_FILES['image'];
        $user = $this->user_repository->getUser($id);
        foreach($data as $key => $value) {
            if (!$this->utils->compareValue($user, $key, $value)) {
                if ($key == 'password') {
                    $user->{'set'.$this->utils->formateKey($key)}(hash('sha256', $value));
                }
                else if ($key == 'image') {
                    $img = $this->utils->uploadFile($value);
                    $user->{'set'.$this->utils->formateKey($key)}($img);
                }
                else {
                    $user->{'set'.$this->utils->formateKey($key)}($value);
                }
            }               
        }
        $this->db_persist->persist($user);

        $this->utils->redirectHome();
    }
    
}