<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of table
 *
 */

require_once('../errors.php');

class Table {
    //Variables publiques
    public $db_connection       = null;
    public $table_name          = null;
    public $PDO_object          = null;
    public $properties          = array();
    public $ignore_properties   = array();
    
    //-----METHODES PRIVEES
    private function _init_properties() {
        $stmt = $this->db_connection->get_columns_by_table_name($this->table_name);
        check_error($stmt);
        
        for ($i = 0; $i < $stmt->rowCount(); $i++) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_ABS, $i);
            $this->properties[$row["Field"]] = "";
        }
        
    }
    
    //-----METHODES PUBLIQUES
    public function __construct($database, $table_name) {
        $this->table_name = htmlentities($table_name);
        $this->db_connection = $database;
        $this->PDO_object = $database->get_connection();
        $this->_init_properties();
    }
    
    public function set_property_value($name, $value) {
        if (!in_array($name, array_keys($this->properties))) {
            return errors("User_setproperty", "La propriété $name n'existe pas");
        }
        
        $this->properties[$name] = $value; 
    }
    
    public function set_properties($array) {
        //Ajouter les informations dans l'objet
        foreach ($array as $key => $value) {
            $ret = $this->set_property_value($key, $value);
                
            //Vérifier que la fonction n'a pas retournée d'erreur
            check_error($ret);
        }
    }
    
    //Supprimer dans l'objet les colonnes qui ne nous intéressent pas
    public function ignore_properties($ignore_properties) {
        foreach ($ignore_properties as $property) {
            if (array_key_exists($property, $this->properties)) {
                unset($this->properties[$property]);
            }
        }
    }
    
}
