<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of user
 *
 */
require_once('../errors.php');
require_once('table.php');

class User extends Table {
    private $I_PROPERTIES = array("date_creation");
    
    private function _generate_token($strong) {
        return bin2hex(openssl_random_pseudo_bytes(64, $strong));
    }
    
//-----METHODES PUBLIQUES
    public function __construct($database) {
        parent::__construct($database, "user");
        parent::ignore_properties($this->I_PROPERTIES);
    }
    
    public function create() {
        //Fixer les valeurs par défaut
        $default_values = array("email_n" => 1, "push" => 1, "point" => 50, "point_niv" => 50, "token" => $this->_generate_token(true));
        $this->properties = array_merge($this->properties, $default_values);
        
        //Hash le mot de passe avant de l'insérer dans la base
        $this->properties["password"] = hash("sha1", $this->properties["password"]);
        
        $request = $this->db_connection->build_insert_request($this->table_name, $this->properties);
                    
        //Préparer la requête
        try {
            $stmt = $this->PDO_object->prepare($request);
        } catch (PDOException $ex) {
            return errors("PDOException", $ex->getMessage());
        }
        
        if(! $stmt) {
            return errors("User_prepare", $stmt->errorInfo()[2]);
        }

        //Lier les données dans la requête
        foreach(array_keys($this->properties) as $column) {
            if(! $stmt->bindParam(":$column", $this->properties[$column])) {
                return errors("User_bindParam", $stmt->errorInfo()[2]);
            }
        }
        
        //Executer la requête
        if (! $stmt->execute()) {
            return errors("User_execute", $stmt->errorInfo()[2]);
        }
        
        return array("token" => $this->properties["token"]);
    }   
    
    public function get_token_by_pseudo_and_password() {
        //Attaque par dictionnaire possible. Ajouter un sel pour une meilleur securité
        $h_passwd = hash("sha1", $this->properties["password"]);
        $request = "SELECT token from $this->table_name WHERE pseudo=:pseudo AND password=:password";
        
        //Préparer la requête
        try {
            $stmt = $this->PDO_object->prepare($request);
        } catch (PDOException $ex) {
            return errors("PDOException", $ex->getMessage());
        }
        
        if(! $stmt) {
            return errors("User_prepare", $stmt->errorInfo()[2]);
        }
        
        //Lier les valeurs à la requête
        if (! ($stmt->bindParam(":pseudo", $this->properties["pseudo"]) and $stmt->bindParam(":password", $h_passwd))) {
            return errors("User_bindParam", $stmt->errorInfo()[2]);
        }
        
        //Executer la requête
        if (! $stmt->execute()) {
            return errors("User_execute", $stmt->errorInfo()[2]);
        }
        
        //Vérifier qu'il existe 1 élément
        if ($stmt->rowCount() != 1) {
            return errors("User_count", "Le pseudo [" . $this->properties["pseudo"] . "] n'existe pas ou est déjà utilisé");
        }
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        //Vérifier si la fonction a bien retourné la clé token et un token
        if (! (array_key_exists("token", $data) && preg_match("/^[a-f0-9]{128}$/", $data["token"]))) {
            return errors("User_token", "Quelque chose s'est mal passée");
        }
        
        return $data;
    }
    
/*
    public function get_all_by_token() {
        $request = "SELECT * from " . $this->table_name . " where token=:token";
        
        //Préparer la requête
        try {
            $stmt = $this->PDO_object->prepare($request);
        } catch (PDOException $ex) {
            return errors("PDOException", $ex->getMessage());
        }
        
        if (! $stmt) {
            return errors("User_prepare", $stmt->errorInfo()[2]);
        }
        
        //Lier les données dans la requête
        if(! $stmt->bindParam(":token", $this->properties["token"])) {
            return errors("User_bindParam", $stmt->errorInfo()[2]);
        }
        
        //Exécuter la requête
        if (! $stmt->execute()) {
            return errors("User_execute", $stmt->errorInfo()[2]);
        }
        
        //Vérifier qu'il existe qu'un seul utilisateur (devrait jamais arriver)
        if ($stmt->rowCount() != 1) {
            return errors("User_count", "L'utilisateur n'existe pas ou est déjà utilisé");
        }
        
        $this->set_properties($stmt->fetch(PDO::FETCH_ASSOC));
        
        return $this->properties["user_id"];
    }
*/
    //-----FIN METHODES PUBLIQUES
}
