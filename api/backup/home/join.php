<?php
require_once("../get_token.php");
require_once("../config/database.php");
require_once("../objects/user_has_workshop.php");
require_once("../objects/workshop.php");


header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json;charset=UTF-8');
header('Access-Control-Allow-Methods: DELETE, HEAD, GET, OPTIONS, POST, PUT');
header('Access-Control-Allow-Headers: Origin, Content-Type, Token, Authorization');
header('Access-Control-Max-Age: 17');


//Initialiser la connexion
$db = new Database();

//Vérifier que l'objet n'a pas retourné d'erreur
check_error($db);

$workshop = new Workshop($db);

//Vérifier que l'objet n'a pas retourné d'erreur
check_error($workshop);

//Initialiser l'objet workshop
$user_has_maison = new User_has_workshop($db);

//Vérifier que l'objet n'a pas retourné d'erreur
check_error($user_has_maison);

//Récupérer les données au format json
$json_data = json_decode(file_get_contents('php://input'), true);

//Vérifier que le json a été parsé correctement et qu'il n'est pas nul
if ($json_data == null) {
    check_error(errors("Join_get_contents", "Le fichier json est vide"));
}

//Vérfier que la clé nom existe dans le json
if (! array_key_exists("name", $json_data)) {
    check_error(errors("Join_json_keys", "L'entrée est invalide"));
}

check_error($stmt = $workshop->get_by_name($json_data["name"]));

if (! ($stmt && $stmt->rowCount() == 1)) {
    check_error(errors("Join_name", "Le nom de l'atelier n'existe pas"));
}

check_error($ret = $user_has_maison->create($token=get_token(), array("name" => $json_data["name"])));

success($ret["success"]);
