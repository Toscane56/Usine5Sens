<?php
require_once("table.php");
/**
 * Description of User_has_workshop
 *
 */
class User_has_workshop extends Table{
    public function __construct($db) {
        parent::__construct($db, "User_has_workshop");
    }

    //public function create($token, $workshop_id) {
    public function create($token, $cond) {
        $column = array_keys($cond)[0];
        //$request = "INSERT INTO " . $this->table_name  . " SELECT user_id, workshop_id FROM user, workshop WHERE token=:token AND workshop_id=:workshop_id";
        $request = "INSERT INTO " . $this->table_name  . " SELECT id_user, id_workshop FROM user, workshop WHERE token=:token AND workshop.$column=:$column";

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

        return array("success" => "Utilisateur lié à un atelier");
    }
}
