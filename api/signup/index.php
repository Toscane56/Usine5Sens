<?php
	include("../config/db.php");

	header('Access-Control-Allow-Origin:*');

	header('Content-Type: application/json;charset=UTF-8');
	header('Access-Control-Allow-Methods: DELETE, HEAD, GET, OPTIONS, POST, PUT');
	header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
	header('Access-Control-Max-Age: 17'); 


	//$request= file_get_contents('test.json');
	$request= file_get_contents('php://input');

	var_dump ($request);
    //$data = json_decode(file_get_contents('php://input'), true);
    //$k= utf8_encode($request);
    $data= json_decode($request, true);

    
  	$email = $data['email'];
    $nom= $data['nom'];
    $prenom= $data['prenom'];
    $pseudo= $data['pseudo'];
    $password= $data['password'];
    $date_creation = date("Y-m-d H:i:s");
    $longitude= $data['lng'];
    $latitude= $data['lat'];

    var_dump ($email);
    echo $email;

	// //Formulation de la requête SQL pour insérer les données dans la table
    $sql1="INSERT INTO user(pseudo, nom, prenom, password, email, date_creation, longitude, latitude, point, point_niv, email_n, push) VALUES('$pseudo', '$nom', '$prenom', '$password', '$email', '$date_creation','$longitude','$latitude', 50, 50, 1, 1)";

    $test = mysqli_query($db, $sql1);
						
	//Si la requete fonctionne
	//if($test) echo "<p class='infoRequete'>Ajout effectué</p>"; 
	// Si la requete ne fonctionne pas
	//else echo "<p class='infoRequete'>Erreur de la requête</p>"; 
    