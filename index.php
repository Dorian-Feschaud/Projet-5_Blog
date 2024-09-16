<?php

define('ROOT_PATH', __DIR__);

require_once 'src/controller/RouteController.php';

session_start();

$full_url = $_SERVER['REQUEST_URI'];
$blog_url = substr($full_url, 6);
if (str_contains($blog_url, '?')) {
    $url = explode('?', $blog_url)[0];
}
else {
    $url = $blog_url;
}

$route_controller = new RouteController();

if (!empty($url)) {
    $parts = explode('/', $url);
    switch (count($parts)) {
        case 1:            
            switch ($parts[0]) {
                case 'register':        
                    $route_controller->register();
                    break;
                case 'login':
                    $route_controller->login();
                    break;
                case 'logout':
                    $route_controller->logout();
                    break;
                case 'contact':
                    $route_controller->contact();
                    break;
                default :
                    $class = ucfirst(substr($parts[0], 0, -1)) . 'Controller';
                    if ($class != 'PostController' && $class != 'UserController') {
                        $route_controller->error();
                    }
                    else {
                        $controller = new $class();
                        $route_controller->showAll($controller);
                    }
                    break;
            }
            break;
        case 2:
            $class = ucfirst(substr($parts[0], 0, -1)) . 'Controller';
            if ($class != 'PostController' && $class != 'UserController') {
                $route_controller->error();
            }
            else {
                $controller = new $class();
                switch (preg_match('/[^0-9]+$/', $parts[1])) {
                    // objects/id
                    case 0:
                        $id = $parts[1];
                        $route_controller->showOne($controller, $id);
                        break;
                    // objects/new
                    case 1:
                        if ($parts[1] == 'new') {
                            $route_controller->new($controller);
                        }
                        else {
                            $route_controller->error();
                        }
                        break;
                }
            }
            break;
        case 3: // objects/id/action
            $id = $parts[1];
            $class = ucfirst(substr($parts[0], 0, -1)) . 'Controller';
            if ($class != 'PostController' && $class != 'UserController') {
                $route_controller->error();
            }
            else {
                $methods = array(
                    'edit',
                    'delete',
                    'author_submission',
                    'valid_author',
                    'refuse_author',
                    'posts'
                );
                $controller = new $class();
                $method = $parts[2];
                if (in_array($method, $methods)) {
                    $route_controller->$method($controller, $id);
                }
                else {
                    $route_controller->error();
                }
            }
            break;
        case 4:
            $class = ucfirst(substr($parts[2], 0, -1)) . 'Controller';
            if ($class == 'CommentController') {
                $controller = new $class();
                switch (preg_match('/[^0-9]+$/', $parts[3])) {
                    // object/id
                    case 0:
                        // $id = $parts[3];
                        // $route_controller->showOne($controller, $id);
                        $route_controller->error();
                        break;
                    // object/method
                    case 1:
                        switch ($parts[3]) {
                            case 'new':
                                $id_parent = $parts[1];
                                $route_controller->actionChildren($controller, $id_parent);
                                break;
                            case 'admin':
                                $class = ucfirst(substr($parts[0], 0, -1)) . 'Controller';
                                if ($class == 'PostController') {
                                    $controller = new $class();
                                    $id_parent = $parts[1];
                                    $route_controller->adminComments($controller, $id_parent);
                                }
                                else {
                                    $route_controller->error();
                                }
                                break;
                            default:
                                $route_controller->error();
                                break;
                        }
                        break;
                }
            }
            else {
                $route_controller->error();
            }
            break;
        case 5:
            $id = $parts[3];
            $class = ucfirst(substr($parts[2], 0, -1)) . 'Controller';
            if ($class == 'CommentController') {
                $controller = new $class();
                $methods = array(
                    'valid_comment',
                    'refuse_comment',
                );
                $method = $parts[4];
                if (in_array($method, $methods)) {
                    $route_controller->$method($controller, $id);
                }
                else {
                    $route_controller->error();
                }
            }
            else {
                $route_controller->error();
            }
            break;
        default :
            break;
    }
}
else {
    $route_controller->homepage();
}