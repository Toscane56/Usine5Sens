<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__.'/../src/DB.php';
require __DIR__.'/../src/Controller.php';
require __DIR__.'/../src/Controllers/UserController.php';
require __DIR__.'/../src/Controllers/WorkshopController.php';
require __DIR__.'/../src/Controllers/ExpositionController.php';

header('content-type: text/plain');

// User: register

$headers = [];

$data = [
    'email' => 'jean@dupont.fr',
    'password' => 'azerty',
    'firstname' => 'Jean',
    'lastname' => 'Dupont'
];

$userController = new \App\Controllers\UserController(true, $headers, $data);

$result = $userController->register();

if ($result['status'] == 'success') {
    echo "User: register OK".PHP_EOL;
    var_dump($result['user']);
    echo PHP_EOL;
}
else {
    echo "User: register FAILED".PHP_EOL;
    echo $result['message'].PHP_EOL;
    die;
}

// User: login

$headers = [];

$data = [
    'email' => 'jean@dupont.fr',
    'password' => 'azerty'
];

$userController = new \App\Controllers\UserController(true, $headers, $data);

$result = $userController->login();

if ($result['status'] == 'success') {
    echo "User: login OK".PHP_EOL;
    var_dump($result['user']);
    echo PHP_EOL;
}
else {
    echo "User: login FAILED".PHP_EOL;
    echo $result['message'].PHP_EOL;
    die;
}

$token = $result['user']['token'];

// User: profile

$headers = [
    'X-AUTH-TOKEN' => $token
];

$data = [];

$userController = new \App\Controllers\UserController(true, $headers, $data);

$result = $userController->profile();

if ($result['status'] == 'success') {
    echo "User: profile OK".PHP_EOL;
    var_dump($result['user']);
    echo PHP_EOL;
}
else {
    echo "User: profile FAILED".PHP_EOL;
    echo $result['message'].PHP_EOL;
}

// User: update

$headers = [
    'X-AUTH-TOKEN' => $token
];

$data = [
    'email' => 'jeanne@dupuis.fr',
    'password' => 'qwerty',
    'firstname' => 'Jeanne',
    'lastname' => 'Dupuis'
];

$userController = new \App\Controllers\UserController(true, $headers, $data);

$result = $userController->update();

if ($result['status'] == 'success') {
    echo "User: update OK".PHP_EOL;
    var_dump($result['user']);
    echo PHP_EOL;
}
else {
    echo "User: update FAILED".PHP_EOL;
    echo $result['message'].PHP_EOL;
}

// Exposition: index

$headers = [];
$data = [];

$expositionController = new \App\Controllers\ExpositionController(true, $headers, $data);

$result = $expositionController->index();

if ($result['status'] == 'success') {
    echo "Exposition: retrieve OK".PHP_EOL;
    var_dump($result['exposition']);
    echo PHP_EOL;
}
else {
    echo "Exposition: retrieve FAILED".PHP_EOL;
    echo $result['message'].PHP_EOL;
}

// Wokshops: index

$headers = [];
$data = [];

$workshopController = new \App\Controllers\WorkshopController(true, $headers, $data);

$result = $workshopController->index();

if ($result['status'] == 'success') {
    echo "Workshops: index OK".PHP_EOL;
    var_dump($result['workshops']);
    echo PHP_EOL;
}
else {
    echo "Workshops: index FAILED".PHP_EOL;
    echo $result['message'].PHP_EOL;
}

// Wokshops: index

$headers = [];
$data = [];

$workshopController = new \App\Controllers\WorkshopController(true, $headers, $data);

$result = $workshopController->index();

if ($result['status'] == 'success') {
    echo "Workshops: index OK".PHP_EOL;
    var_dump($result['workshops']);
    echo PHP_EOL;
}
else {
    echo "Workshops: index FAILED".PHP_EOL;
    echo $result['message'].PHP_EOL;
}

if (count($result['workshops']))
{
    $workshop = $result['workshops'][0];

    // Workshop: booking

    $headers = [
        'X-AUTH-TOKEN' => $token
    ];

    $data = [
        'workshop_id' => $workshop['id']
    ];

    $workshopController = new \App\Controllers\WorkshopController(true, $headers, $data);

    $result = $workshopController->book();

    if ($result['status'] == 'success') {
        echo "Workshops: book OK".PHP_EOL;
        var_dump($result['reservation']);
        echo PHP_EOL;
    }
    else {
        echo "Workshops: book FAILED".PHP_EOL;
        echo $result['message'].PHP_EOL;
        die;
    }

    // Workshop: reservations

    $headers = [
        'X-AUTH-TOKEN' => $token
    ];

    $data = [];

    $workshopController = new \App\Controllers\WorkshopController(true, $headers, $data);

    $result = $workshopController->reservations();

    if ($result['status'] == 'success') {
        echo "Workshops: reservations OK".PHP_EOL;
        var_dump($result['workshops']);
        echo PHP_EOL;
    }
    else {
        echo "Workshops: reservations FAILED".PHP_EOL;
        echo $result['message'].PHP_EOL;
    }

    // Workshop: delete

    $headers = [
        'X-AUTH-TOKEN' => $token
    ];

    $data = [
        'workshop_id' => $workshop['id']
    ];

    $workshopController = new \App\Controllers\WorkshopController(true, $headers, $data);

    $result = $workshopController->remove();

    if ($result['status'] == 'success') {
        echo "Workshops: delete OK".PHP_EOL;
        echo PHP_EOL;
    }
    else {
        echo "Workshops: delete FAILED".PHP_EOL;
        echo $result['message'].PHP_EOL;
    }
}
else {
    echo "Workshops tests failed: no workshop available in database for today.";
}