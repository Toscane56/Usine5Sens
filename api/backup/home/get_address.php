<?php
require_once("../get_token.php");
require_once("../errors.php");
require_once("../config/database.php");
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

//Initialiser l'objet workshop
$workshop = new Workshop($db);

//Vérifier que l'objet n'a pas retourné d'erreur
check_error($workshop);

$stmt = $workshop->get_workshop_by_token(get_token());

check_error($stmt);

$addresses = array();

//Filtrer les valeurs retournées
for ($i = 0; $i < $stmt->rowCount(); $i++) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_ABS, $i);
    $tmp = array();

    foreach($row as $key => $value) {
        $tmp[$key] = htmlentities($value);
    }

    $addresses[] = $tmp;
}

success("Les adresses des ateliers ont été récupérés", array("addresses" => $addresses));
