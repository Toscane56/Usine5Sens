<?php

namespace App;
//Définition de tous les headers nécessaires
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS, POST');
header('Access-Control-Allow-Headers: Access-Control-Request-Headers, Access-Control-Request-Method, referer, user-agent, content-type, X-AUTH-TOKEN');
header('Access-Control-Allow-Credentials: true');

if (strtolower($_SERVER['REQUEST_METHOD']) == 'options')
{
    http_response_code(200);
    die;
}

$endpoints = [
    //association des entités aux actions possibles
    'user' => ['register', 'login', 'profile', 'update'],
    'exposition' => ['index'],
    'workshop' => ['index', 'book', 'remove', 'reservations']
];

if (isset($_GET['entity']) && isset($_GET['action']))
{
    $entity = $_GET['entity'];
    $action = $_GET['action'];

    if (isset($endpoints[$entity]))
    {
        if (in_array($action, $endpoints[$entity]))
        {
            header('Content-Type: application/json;charset=UTF-8');
            header('Access-Control-Max-Age: 17');

            $controller = ucfirst($entity).'Controller';
            require __DIR__."/../src/Controller.php";
            require __DIR__."/../src/DB.php";
            require __DIR__."/../src/Controllers/$controller.php";
            $controller = "\App\Controllers\\$controller";
            $instance = new $controller;
            $instance->$action();
            die;
        }
    }
}

include (__DIR__.'/guide.php');