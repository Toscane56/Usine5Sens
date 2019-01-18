<?php
require_once("errors.php");

function get_token() {
    if (!  array_key_exists("Token", apache_request_headers())) {
        check_error(errors("Get_token", "Le token n'existe pas"));
    }
    
    $token = apache_request_headers()["Token"];
    
    if (! preg_match("/^[a-f0-9]{128}$/", $token)) {
        check_error(errors("Get_token", "Le format du token est invalide"));
    }
    
    return $token;
}

