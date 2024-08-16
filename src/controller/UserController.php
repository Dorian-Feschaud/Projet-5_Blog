<?php

require_once 'src/repository/UserRepository.php';
require_once 'src/lib/Utils.php';
require_once 'src/lib/DbPersist.php';

class UserController {
    
    private UserRepository $user_repository;
    
    private Utils $utils;
    
    private DbPersist $db_persist;
    
    public function __construct(){
        $this->user_repository = new UserRepository();
        $this->utils = new Utils();
        $this->db_persist = new DbPersist();
    }
    
    public function login(array $data):void {
        $user_id = $this->user_repository->login($data);
        switch ($user_id) {
            case 0: // login error
                var_dump('invalid identifiant');
                die();
                break;
                default: // login successful
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user_id; // hash
                break;
            }
            
            $this->utils->redirectHome();
        }
        
        public function register(array $data):void {
            $this->user_repository->register($data);
            
            $this->utils->redirectHome();
        }
        
        public function logout():void {
            session_destroy();
            $this->utils->redirectHome();
        }

        public function profil(Twig\Environment $twig, int $id):void {
            $user = $this->user_repository->getUser($id);
            
            echo $twig->render('profil.html.twig', ['user' => $user]);
        }
        
        public function showOne(Twig\Environment $twig, int $id):void {
            $user = $this->user_repository->getUser($id);
            
            echo $twig->render('user.html.twig', ['user' => $user]);
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
            
            echo $twig->render('users.html.twig', ['users' => $users]);
        }
        
        public function showAllByRole(Twig\Environment $twig, string $role):void {
            $users = $this->user_repository->getUsersByRole($role);
            
            echo $twig->render('users.html.twig', ['users' => $users]);
        }

        public function posts(Twig\Environment $twig, int $id):void {
            $posts = $this->user_repository->getPosts($id);

            echo $twig->render('posts.html.twig', ['posts' => $posts]);
        }
        
    }