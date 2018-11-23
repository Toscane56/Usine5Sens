<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

function errors($type, $msg) {
    $err = array(
        "status" => "error",
        "type" => $type,
        "message" => $msg
    );
    
    return $err;
}

function check_error($obj) {
    //Vérifier que l'objet n'a pas retourné d'erreur
    if (is_array($obj) && array_key_exists("status", $obj) && $obj["status"] == "error") {
        header('Content-Type: application/json');
        echo json_encode($obj);
        die();
    }
}

function success($msg, $options=array()) {
    header('Content-Type: application/json');
    $response = array("status" => "success", "message" => $msg);
    
    foreach ($options as $key => $value) {
        $response[$key] = $value;
    }
    
    echo json_encode($response);
}