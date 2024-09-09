<?php

// require_once 'src/controller/HomeController.php';
// require_once 'src/controller/PostController.php';
// require_once 'src/controller/CommentController.php';
// require_once 'src/controller/UserController.php';
// require_once 'src/form/PostForm.php';
// require_once 'src/form/CommentForm.php';
// require_once 'src/form/UserForm.php';
// require_once 'src/form/UserForm.php';
// require_once 'src/lib/TwigLoader.php';
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
                    $controller = new $class(); // gerer page 404 avec un try sur le controller
                    $route_controller->showAll($controller);
                    break;
            }
            break;
        case 2:
            $class = ucfirst(substr($parts[0], 0, -1)) . 'Controller';
            $controller = new $class(); // gerer page 404 avec un try sur le controller
            switch (preg_match('/[^0-9]+$/', $parts[1])) {
                // objects/id
                case 0:
                    $id = $parts[1];
                    $route_controller->showOne($controller, $id);
                    break;
                // objects/new
                case 1:
                    $route_controller->action($controller);
                    break;
            }
            break;
        case 3: // objects/id/action
            $id = $parts[1];
            $class = ucfirst(substr($parts[0], 0, -1)) . 'Controller';
            $controller = new $class(); // gerer page 404 avec un try sur le controller
            $method = $parts[2];
            $route_controller->$method($controller, $id);
            break;
        case 4:
            $class = ucfirst(substr($parts[2], 0, -1)) . 'Controller';
            $controller = new $class(); // gerer page 404 avec un try sur le controller
            switch (preg_match('/[^0-9]+$/', $parts[3])) {
                // object/id
                case 0:
                    $id = $parts[3];
                    $route_controller->showOne($controller, $id);
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
                            $controller = new $class(); // gerer page 404 avec un try sur le controller
                            $id_parent = $parts[1];
                            $route_controller->adminComments($controller, $id_parent);
                            break;
                    }
                    break;
            }
            break;
        case 5:
            $id = $parts[3];
            $class = ucfirst(substr($parts[2], 0, -1)) . 'Controller';
            $controller = new $class(); // gerer page 404 avec un try sur le controller
            $method = $parts[4];
            $route_controller->$method($controller, $id);
            break;
        default :
            break;
    }
}
else {
    $route_controller->homepage();
}