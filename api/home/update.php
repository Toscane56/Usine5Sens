<?php


require_once("../get_token.php");
require_once("../config/database.php");
require_once("../objects/home.php");
require_once("../errors.php");


header('Access-Control-Allow-Origin: *'); 
header('Content-Type: application/json;charset=UTF-8'); 
header('Access-Control-Allow-Methods: DELETE, HEAD, GET, OPTIONS, POST, PUT'); 
header('Access-Control-Allow-Headers: Origin, Content-Type, Token, Authorization'); 
header('Access-Control-Max-Age: 17'); 


//Initialiser la connexion
$db = new Database();

//Vérifier que l'objet n'a pas retourné d'erreur
check_error($db);

//Initialiser l'objet home
$home = new Home($db);

//Vérifier que l'objet n'a pas retourné d'erreur
check_error($home);

//Récupérer les données au format json
$json_data = json_decode(file_get_contents('php://input'), true);

if ($json_data == null) {
    return check_error(errors("Create_get_contents", "Le fichier json est vide"));
}

//Affecter les valeurs 
foreach (array_keys($json_data) as $column) {
    $ret = $home->set_property_value($column, $json_data[$column]);

    //Vérifier que la fonction n'a pas retournée d'erreur
    check_error($ret);
}

check_error($ret = $home->update(get_token()));

success($ret);
