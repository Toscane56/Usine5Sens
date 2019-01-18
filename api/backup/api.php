<?php
	include("db.php");

	header("Access-Control-Allow-Origin:*");

	$requete = "SELECT * FROM user" ;
	$resultat = $db->query($requete);

	$res['users']=[];

	while($user = $resultat->fetch_assoc()){
		$res['users'][] = $user;
	}

	echo json_encode($res);
	

	