<?php
require_once("../config/database.php");
require_once("../objects/user.php");
require_once("../errors.php");


header("Access-Control-Allow-Origin:*");
header('Content-Type: application/json;charset=UTF-8');
header('Access-Control-Allow-Methods: DELETE, HEAD, GET, OPTIONS, POST, PUT');
header('Access-Control-Allow-Headers: Origin, Content-Type, Token, Authorization');
header('Access-Control-Max-Age: 17'); 

 //Initialiser la connexion
$db = new Database();

//Vérifier que l'objet n'a pas retourné d'erreur
check_error($db);

//Initialiser l'objet user
$user = new User($db);

//Vérifier que l'objet n'a pas retourné d'erreur
check_error($user);

//Récupérer les données au format json
$json_data = json_decode(file_get_contents('php://input'), true);

//Vérifier que le json a strictement les paramètres pseudo et password
if (! (sizeof($json_data) == 2 && 
        array_key_exists("pseudo", $json_data) && 
        array_key_exists("password", $json_data))) {
    return check_error(errors("get_json", "L'entrée est vide"));
}

//Ajouter les valeurs du json dans l'objet user
$user->set_properties($json_data);

//Récupérer le token associé à l'utilisateur : {"token": [0-9a-f]{128}}
$data = $user->get_token_by_pseudo_and_password();
        
//Vérifier que la fonction n'a pas retournée d'erreur
check_error($data);

$data["pseudo"] = htmlentities($json_data["pseudo"]);
success("Jeton reçu", $options=$data);

