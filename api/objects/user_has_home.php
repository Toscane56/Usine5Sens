<?php
require_once("table.php");
/**
 * Description of User_has_home
 *
 */
class User_has_home extends Table{
    public function __construct($db) {
        parent::__construct($db, "user_has_maison");
    }
    
    //public function create($token, $maison_id) {
    public function create($token, $cond) {
        $column = array_keys($cond)[0];
        //$request = "INSERT INTO " . $this->table_name  . " SELECT user_id, maison_id FROM user, maison WHERE token=:token AND maison_id=:maison_id";
        $request = "INSERT INTO " . $this->table_name  . " SELECT user_id, maison_id FROM user, maison WHERE token=:token AND maison.$column=:$column";
        
        //Préparer la requête
        try {
            $stmt = $this->PDO_object->prepare($request);
        } catch (PDOException $ex) {
            return errors("PDOException", $ex->getMessage());
        }
        
        if (! $stmt) {
            return errors("UserHM_prepare", $stmt->errorInfo()[2]);
        }
        
        //Lier les données dans la requête
        if(! ($stmt->bindParam(":token", $token) && $stmt->bindParam(":$column", $cond[$column]))) {
            return errors("UserHM_bindParam", $stmt->errorInfo()[2]);
        }
        
        //Exécuter la requête
        if (! $stmt->execute()) {
            return errors("UserHM_execute", $stmt->errorInfo()[2]);
        }
        
        return array("success" => "Utilisateur lié à une maison");
    }
}
