<?php
require_once '../get_token.php';
require_once('../errors.php');
require_once('table.php');

class Home extends Table {
    
    private function _build_update_request($columns) {
        $str_to_label = function($s) { return "maison.$s = :$s"; };
        
        $request = "UPDATE " . $this->table_name;
        $request .= " INNER JOIN user_has_maison";
        $request .= " ON maison.maison_id = user_has_maison.maison_maison_id";
        $request .= " INNER JOIN user";
        $request .= " ON user_has_maison.user_user_id = user.user_id";
        $request .= " SET " . implode(', ', array_map($str_to_label, array_keys($columns)));
        $request .= " WHERE user.token=:token";
        
        return $request;
    }
    
    
    private function _set_id_by_name() {
        $request = "SELECT maison_id from " . $this->table_name . " where nom=:nom";
        
        //Préparer la requête
        try {
            $stmt = $this->PDO_object->prepare($request);
        } catch (PDOException $ex) {
            return errors("PDOException", $ex->getMessage());
        }
        
        if (! $stmt) {
            return errors("Home_prepare", $stmt->errorInfo()[2]);
        }
        
        //Lier les données dans la requête
        if(! $stmt->bindParam(":nom", $this->properties["nom"])) {
            return errors("Home_bindParam", $stmt->errorInfo()[2]);
        }
        
        //Exécuter la requête
        if (! $stmt->execute()) {
            return errors("Home_execute", $stmt->errorInfo()[2]);
        }
        
        //Vérifier qu'il existe qu'un seul compteur (devrait jamais arriver)
        if ($stmt->rowCount() != 1) {
            return errors("Home_count", "Le nom n'existe pas ou est déjà utilisé");
        }
        
        $this->set_property_value("maison_id", $stmt->fetch(PDO::FETCH_ASSOC)["maison_id"]);
        return $this->properties["maison_id"];
    }
    
    //-----METHODES PUBLIQUES
    public function __construct($database) {
        parent::__construct($database, "maison");
    }
    
    
    public function create() {
        $request = $this->db_connection->build_insert_request($this->table_name, $this->properties);

        //Préparer la requête
        try {
            $stmt = $this->PDO_object->prepare($request);
        } catch (PDOException $ex) {
            return errors("PDOException", $ex->getMessage());
        }
        
        if(! $stmt) {
            return errors("Home_prepare", $stmt->errorInfo()[2]);
        }

        //Lier les données dans la requête
        foreach(array_keys($this->properties) as $column) {
            if(! $stmt->bindParam(":$column", $this->properties[$column])) {
                return errors("Home_bindParam", $stmt->errorInfo()[2]);
            }
        }
        
        //Executer la requête
        if (! $stmt->execute()) {
            return errors("Home_execute", $stmt->errorInfo()[2]);
        }
        
        //Récupérer l'id de la maison via l'identifiant linky
        $this->_set_id_by_name();
        
        return array("status" => "success");
    }
    
    
    public function update($token) {      
        $data = array();
        foreach ($this->properties as $key => $value) {
            if ($value != null) {
                $data[$key] = $value;
            }
        }
        unset($key);
        unset($value);
        
        if (sizeof($data) == 0) {
            return errors("Home_update", "Aucun élément à mettre à jour");
        }
        //return(errors("debug", $data));
        
        $request = $this->_build_update_request($data);
        
        //Ajouter le token aux données que l'on souhaite lier
        $data["token"] = $token;

        //Préparer la requête
        try {
            $stmt = $this->PDO_object->prepare($request);
        } catch (PDOException $ex) {
            return errors("PDOException", $ex->getMessage());
        }
        
        if(! $stmt) {
            return errors("Home_prepare", $stmt->errorInfo()[2]);
        }

        //Lier et Executer la requête
        if (! $stmt->execute($data)) {
            return errors("Home_execute", $stmt->errorInfo()[2]);
        }
        
        return array("status" => "Mise à jour des informations de la maison faite");
    }
    
    
    public function get_all_name_and_cp() {
        $request = "SELECT nom, cp FROM maison";
        
        $stmt = $this->PDO_object->query($request);
        
        if(! $stmt) {
            return errors("Home_query",$stmt->errorInfo()[2]);
        }
        
        return $stmt;
    }
    
    public function  get_by_name($name) {
        $request = "SELECT * FROM maison WHERE nom=:nom";
        
        //Préparer la requête
        try {
            $stmt = $this->PDO_object->prepare($request);
        } catch (PDOException $ex) {
            return errors("PDOException", $ex->getMessage());
        }
        
        if(! $stmt) {
            return errors("Home_prepare", $stmt->errorInfo()[2]);
        }

        //Lier les données dans la requête
        if(! $stmt->bindParam(":nom", $name)) {
            return errors("Home_bindParam", $stmt->errorInfo()[2]);
        }
        
        //Executer la requête
        if (! $stmt->execute()) {
            return errors("Home_execute", $stmt->errorInfo()[2]);
        }
        
        return $stmt;
    }
    
    
    public function get_address_by_token($token) {
        $request = "SELECT voie, immeuble, bp, cp, ville, compt_linky "
                . "FROM maison "
                . "LEFT JOIN user_has_maison ON user_has_maison.maison_maison_id = maison.maison_id "
                . "LEFT JOIN user ON user_has_maison.user_user_id = user.user_id "
                . "WHERE user.token=:token";
        
        //Préparer la requête
        try {
            $stmt = $this->PDO_object->prepare($request);
        } catch (PDOException $ex) {
            return errors("PDOException", $ex->getMessage());
        }
        
        if(! $stmt) {
            return errors("Home_prepare", $stmt->errorInfo()[2]);
        }

        //Lier les données dans la requête
        if(! $stmt->bindParam(":token", $token)) {
            return errors("Home_bindParam", $stmt->errorInfo()[2]);
        }
        
        //Executer la requête
        if (! $stmt->execute()) {
            return errors("Home_execute", $stmt->errorInfo()[2]);
        }
        
        return $stmt;
    }   
}