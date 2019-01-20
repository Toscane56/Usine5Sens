<?php

namespace App;

use PDO;
use PDOException;

class DB
{
    protected $db;

    public function __construct()
    {
        $config = include (__DIR__.'/../config.php');
        $mysql = $config['mysql'];

        try {
            $this->db = new PDO("mysql:host={$mysql['host']}:{$mysql['port']};dbname={$mysql['database']};charset=utf8", $mysql['user'], $mysql['password']);
            //vérifie qu'il n'y a pas de problèmes de connexion à la bdd
        }
        catch (PDOException $e) {
            $this->failure("database", $e->getMessage()); //s'il y a un problème, récupère le message d'erreur
        }
    }

    protected function buildQuery($entity, $type = 'select', $where = [], $updates = [])
    //Fonction permettant de préparer les requetes envoyées au serveur
    {
        $where_clause = implode(' = ? AND ', array_keys($where)).' = ?';
        if (empty($where_clause)) $where_clause = '1';

        $set_clause = implode(' = ?, ', array_keys($updates)).' = ?';

        if ($type == 'delete') {
            $raw = "DELETE FROM $entity WHERE $where_clause"; 
            //prépare une requete de suppression de données dans la bdd avec une clause
        }
        elseif ($type == 'update') {
            if (empty($set_clause)) $this->failure('query', 'Updates data required');
            $raw = "UPDATE $entity SET $set_clause WHERE $where_clause";
            // prépare une requete pour mettre à jour une entité de la bdd avec une clause
        }
        elseif ($type == 'insert') {
            if (empty($set_clause)) $this->failure('query', 'Updates data required');
            $raw = "INSERT INTO $entity SET $set_clause";
            // prépare une requete pour insérer des nouvelles données dans une entité dans la base de données
        }
        else {
            $raw = "SELECT * FROM $entity WHERE $where_clause";
            // permet de récuperer toutes les données d'une entité avec une clause
        }

        $query = null;

        try {
            $query = $this->db->prepare($raw); //prépare la requete
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute(array_merge(array_values($updates), array_values($where))); //envoi de la requete
        }
        catch (\PDOException $e) {
            $this->failure("database", $e->getMessage()); //transmet un message en cas d'erreur
        }

        return $query;
    }

    public function fetch($entity, $attrs = [])
    {
        //fonction préparant la sélection d'une propriété dans la bdd
        $query = $this->buildQuery($entity, 'select', $attrs);
        return $query->fetch();
    }

    public function fetchAll($entity, $attrs = [])
    {
        //fonction préparant la sélection de plusieurs propriétes dans la bdd
        $query = $this->buildQuery($entity, 'select', $attrs);
        return $query->fetchAll();
    }

    public function insert($entity, $data = [])
    {
        //fonction préparant l'insertion de nouvelles données dans un champ
        $this->buildQuery($entity, 'insert', [], $data);
        $id = $this->db->lastInsertId();
        return $this->fetch($entity, ['id' => $id]);
    }

    public function update($entity, $where = [], $data = [])
    //fonction préparant la mise à jour des données dans la bdd
    {
        $this->buildQuery($entity, 'update', $where, $data);
        return $this->fetch($entity, $where);
    }

    public function delete($entity, $where = [])
    //fonction préparant la suppression de propriétés de la bdd
    //
    {
        return $this->buildQuery($entity, 'delete', $where);
    }

    public function query($raw)
    // Fonction permettant de préparer l'envoi des données
    {
        $query = null;

        try {
            $query = $this->db->prepare($raw);
            $query->setFetchMode(PDO::FETCH_ASSOC);
        }
        catch (\PDOException $e) {
            $this->failure("database", $e->getMessage());
        }

        return $query;
    }

    protected function failure($error, $message)
    // Fonction permettant d'encoder le message d'erreur (erreur 500 donc serveur)
    {
        http_response_code(500);

        echo json_encode([
            'status' => 'error',
            'message' => $message,
            'error' => $error
        ]);

        die;
    }
}