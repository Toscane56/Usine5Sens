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
        }
        catch (PDOException $e) {
            $this->failure("database", $e->getMessage());
        }
    }

    protected function buildQuery($entity, $type = 'select', $where = [], $updates = [])
    {
        $where_clause = implode(' = ? AND ', array_keys($where)).' = ?';
        if (empty($where_clause)) $where_clause = '1';

        $set_clause = implode(' = ?, ', array_keys($updates)).' = ?';

        if ($type == 'delete') {
            $raw = "DELETE FROM $entity WHERE $where_clause";
        }
        elseif ($type == 'update') {
            if (empty($set_clause)) $this->failure('query', 'Updates data required');
            $raw = "UPDATE $entity SET $set_clause WHERE $where_clause";
        }
        elseif ($type == 'insert') {
            if (empty($set_clause)) $this->failure('query', 'Updates data required');
            $raw = "INSERT INTO $entity SET $set_clause";
        }
        else {
            $raw = "SELECT * FROM $entity WHERE $where_clause";
        }

        $query = null;

        try {
            $query = $this->db->prepare($raw);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute(array_merge(array_values($updates), array_values($where)));
        }
        catch (\PDOException $e) {
            $this->failure("database", $e->getMessage());
        }

        return $query;
    }

    public function fetch($entity, $attrs = [])
    {
        $query = $this->buildQuery($entity, 'select', $attrs);
        return $query->fetch();
    }

    public function fetchAll($entity, $attrs = [])
    {
        $query = $this->buildQuery($entity, 'select', $attrs);
        return $query->fetchAll();
    }

    public function insert($entity, $data = [])
    {
        $this->buildQuery($entity, 'insert', [], $data);
        $id = $this->db->lastInsertId();
        return $this->fetch($entity, ['id' => $id]);
    }

    public function update($entity, $where = [], $data = [])
    {
        $this->buildQuery($entity, 'update', $where, $data);
        return $this->fetch($entity, $where);
    }

    public function delete($entity, $where = [])
    {
        return $this->buildQuery($entity, 'delete', $where);
    }

    public function query($raw)
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