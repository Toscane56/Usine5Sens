<?php
require_once '../get_token.php';
require_once('../errors.php');
require_once('table.php');

class Workshop extends Table {

    private function _build_update_request($columns) {
        $str_to_label = function($s) { return "workshop.$s = :$s"; };

        $request = "UPDATE " . $this->table_name;
        $request .= " INNER JOIN user_has_workshop";
        $request .= " ON workshop.id_workshop = user_has_workshop.workshop_id_workshop";
        $request .= " INNER JOIN user";
        $request .= " ON user_has_workshop.user_id_user = user.id_user";
        $request .= " SET " . implode(', ', array_map($str_to_label, array_keys($columns)));
        $request .= " WHERE user.token=:token";

        return $request;
    }


    private function _set_id_by_name() {
        $request = "SELECT id_workshop from " . $this->table_name . " where name=:name";

        //Préparer la requête
        try {
            $stmt = $this->PDO_object->prepare($request);
        } catch (PDOException $ex) {
            return errors("PDOException", $ex->getMessage());
        }

        if (! $stmt) {
            return errors("Workshop_prepare", $stmt->errorInfo()[2]);
        }

        //Lier les données dans la requête
        if(! $stmt->bindParam(":name", $this->properties["name"])) {
            return errors("Workshop_bindParam", $stmt->errorInfo()[2]);
        }

        //Exécuter la requête
        if (! $stmt->execute()) {
            return errors("Workshop_execute", $stmt->errorInfo()[2]);
        }

        //Vérifier qu'il existe qu'un seul compteur (devrait jamais arriver)
        if ($stmt->rowCount() != 1) {
            return errors("Workshop_count", "Le nom n'existe pas ou est déjà utilisé");
        }

        $this->set_property_value("id_workshop", $stmt->fetch(PDO::FETCH_ASSOC)["id_workshop"]);
        return $this->properties["id_workshop"];
    }

    //-----METHODES PUBLIQUES
    public function __construct($database) {
        parent::__construct($database, "workshop");
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
            return errors("Workshop_prepare", $stmt->errorInfo()[2]);
        }

        //Lier les données dans la requête
        foreach(array_keys($this->properties) as $column) {
            if(! $stmt->bindParam(":$column", $this->properties[$column])) {
                return errors("Workshop_bindParam", $stmt->errorInfo()[2]);
            }
        }

        //Executer la requête
        if (! $stmt->execute()) {
            return errors("Workshop_execute", $stmt->errorInfo()[2]);
        }

        //Récupérer l'id du workshop
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
            return errors("Workshop_update", "Aucun élément à mettre à jour");
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
            return errors("Workshop_prepare", $stmt->errorInfo()[2]);
        }

        //Lier et Executer la requête
        if (! $stmt->execute($data)) {
            return errors("Workshop_execute", $stmt->errorInfo()[2]);
        }

        return array("status" => "Mise à jour des informations de l'atelier faite");
    }


    public function get_all_name_and_cp() {
        $request = "SELECT name FROM workshop";

        $stmt = $this->PDO_object->query($request);

        if(! $stmt) {
            return errors("Workshop_query",$stmt->errorInfo()[2]);
        }

        return $stmt;
    }

    public function  get_by_name($name) {
        $request = "SELECT * FROM workshop WHERE name=:name";

        //Préparer la requête
        try {
            $stmt = $this->PDO_object->prepare($request);
        } catch (PDOException $ex) {
            return errors("PDOException", $ex->getMessage());
        }

        if(! $stmt) {
            return errors("Workshop_prepare", $stmt->errorInfo()[2]);
        }

        //Lier les données dans la requête
        if(! $stmt->bindParam(":name", $name)) {
            return errors("Workshop_bindParam", $stmt->errorInfo()[2]);
        }

        //Executer la requête
        if (! $stmt->execute()) {
            return errors("Workshop_execute", $stmt->errorInfo()[2]);
        }

        return $stmt;
    }


    public function get_workshop_by_token($token) {
        $request = "SELECT name "
                . "FROM workshop "
                . "LEFT JOIN user_has_workshop ON user_has_workshop.workshop_workshop_id = workshop.workshop_id "
                . "LEFT JOIN user ON user_has_workshop.user_user_id = user.user_id "
                . "WHERE user.token=:token";

        //Préparer la requête
        try {
            $stmt = $this->PDO_object->prepare($request);
        } catch (PDOException $ex) {
            return errors("PDOException", $ex->getMessage());
        }

        if(! $stmt) {
            return errors("Workshop_prepare", $stmt->errorInfo()[2]);
        }

        //Lier les données dans la requête
        if(! $stmt->bindParam(":token", $token)) {
            return errors("Workshop_bindParam", $stmt->errorInfo()[2]);
        }

        //Executer la requête
        if (! $stmt->execute()) {
            return errors("Workshop_execute", $stmt->errorInfo()[2]);
        }

        return $stmt;
    }
}
