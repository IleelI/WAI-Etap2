<?php
    session_start();
    require_once 'routes.php';
    $requested_url = $_GET['action'];
    $routeFound = false;
    foreach ($routes as $key => $route) {
        if ($key === '/'.$requested_url) {
            $routeFound = true;
            break;
        }
    }
    if ($routeFound === false) {
        require '404.php';
    }
    else {
        require $routes['/'.$requested_url].'.php';
    }
?>