<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of database
 *
 * @author 
 */
require_once('../errors.php');

class Database {
    //-----DEBUT Propriétés privées
    //Détails de la configuration (valeurs par défauts)
    private $host       = "localhost";
    private $name       = "homright";
    private $user       = "root";
    private $passwd     = "";
    private $charset    = "utf8mb4";
    //-----FIN propriétés privées
    //
    //-----Propriétés publiques
    public $connection = null;
    //-----FIN Proprietes privees
    
    
    //-----Constructeur
    public function __construct() {
        check_error($this->set_connection());
    }
    
    //-----DEBUT Mutateurs
    public function set_host($host) {
        $this->host = $host;
        return true;
    }
    
    public function set_name($dbname) {
        $this->name = $dbname;
        return true;
    }
    
    public function set_user($username) {
        $this->user = $username;
        return true;
    }
    
    public function set_passwd($password) {
        $this->passwd = $password;
        return true;
    }
    
    public function set_charset($encoding) {
        $this->charset = $encoding;
        return true;
    }
    
    public function set_connection() {
        //Connexion à la base de données
        try {
            $this->connection = new PDO("mysql:host=$this->host;dbname=$this->name;charset=$this->charset", $this->user, $this->passwd);    
        } catch (PDOException $ex) {
            return errors("PDOException", $ex->getMessage());
        }
    }
    //-----FIN Mutateurs
    
    //-----DEBUT accesseurs
    public function get_connection() {
        return $this->connection;
    }
    
    public function get_columns_by_table_name($table_name) {
        //Récupérer les colonnes de la table
        //Impossible de créer une requête préparée
        //htmlentities : evite les injections sql
        $req = "show columns from " . htmlentities($table_name);
        
        if (! $stmt = $this->connection->query($req)) {
            return errors("Database_query", "$req n'a pu être exécutée");
        }

        return $stmt;
    }
    //-----FIN accesseurs
    
     public function build_insert_request($table_name, $properties) {
        //Génération dynamique de requête insert
        //Exemple :  
        //INSERT INTO \
        //  user(user_id,avatar,pseudo,email,password,nom,prenom,date_creation,budget,longitude,latitude,point,point_niv,email_n,push) \
        //  VALUES (:user_id,:avatar,:pseudo,:email,:password,:nom,:prenom,:date_creation,:budget,:longitude,:latitude,:point,:point_niv,:email_n,:push) \
        $str_to_label = function($s) { return ":$s"; };
        
        $req = "INSERT INTO ";
        $req .= $table_name;
        $req .= "(";
        $req .= implode(",", array_keys($properties));
        $req .= ") VALUES (";
        $req .= implode(',', array_map($str_to_label, array_keys($properties)));
        $req .= ")";

        return $req;
    }
}