<?php
header("token: 123456");
require_once('../config/database.php');
require_once('../objects/user.php');
//require_once '../users/get.php';
require_once('../errors.php');
//require_once '../objects/home.php';

//printf("test.php\n");

$database = new Database();
check_error($database);

$user = new User($database);
check_error($database);

//$home = new Home($database);

//$user->properties;
//check_error($user->create());
//var_dump($user->get_token_by_pseudo_and_password("k", "k"));