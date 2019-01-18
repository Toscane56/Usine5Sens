<?php

namespace App;

$endpoints = [
    'user' => ['register', 'login', 'profile', 'update'],
    'expositions' => ['index'],
    'workshops' => ['index', 'book'],
    'reservations' => ['index', 'delete'],
];

if (isset($_GET['entity']) && isset($_GET['action']))
{
    $entity = $_GET['entity'];
    $action = $_GET['action'];

    if (isset($endpoints[$entity]))
    {
        if (in_array($action, $endpoints[$entity]))
        {
            header('Access-Control-Allow-Origin: *');
            header('Content-Type: application/json;charset=UTF-8');
            header('Access-Control-Allow-Methods: GET, POST');
            header('Access-Control-Allow-Headers: Origin, Content-Type, X-AUTH-TOKEN');
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