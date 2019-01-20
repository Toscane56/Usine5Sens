<?php

namespace App;

class Controller
{
    protected $testing;
    protected $data;
    protected $headers;
    protected $db;

    public function __construct($testing = false, $headers = [], $data = null)
    {
        $this->testing = $testing;
        $this->data = is_null($data) ? json_decode(file_get_contents('php://input'), true) : $data;

        if (count($headers) == 0) {
            foreach ($_SERVER as $name => $value)
            {
                if (substr($name, 0, 5) == 'HTTP_')
                {
                    $headers[strtoupper(str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5))))))] = $value;
                }
            }
        }

        $this->headers = $headers;

        $this->db = new DB();
    }

    // Request

    public function getHeader($name)
    //Fonction permettant d'établir le Header présent dans les requetes
    {
        return isset($this->headers[$name]) ? $this->headers[$name] : null;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getValue($key)
    {
        if (isset($this->data[$key]))
            return $this->data[$key];
        return null;
    }

    // Auth

    public function getToken()
    {
        return isset($this->headers['X-AUTH-TOKEN']) ? $this->headers['X-AUTH-TOKEN'] : '';
    }

    public function getUser()
    // Fonction permettant de connaitre l'utilisateur connecté (établir une session grâce au token)
    {
        $token = $this->getToken(); //récupère le token
        if (empty($token)) return $this->error('Invalid token'); //envoie un message d'erreur si le token n'est pas valide

        $user = $this->db->fetch('users', ['token' => $token]); //vérifie que le token correspond à celui de la bdd associé à un utilisateur
        if (!$user) return $this->error('Invalid token'); //envoie un message d'erreur si le token n'est pas valide

        return $user;
    }

    // Responses

    public function success($message, $data = [])
    // Fonction permettant d'envoyer le message lorsque la requete réussie
    {
        return $this->send(array_merge(['status' => 'success', 'message' => $message], $data));
    }

    public function error($message, $data = [], $code = 400)
    // Fonction permettant d'envoyer un message d'erreur lorsque la requete échoue
    {
        return $this->send(array_merge(['status' => 'error', 'message' => $message], $data), $code);
    }

    public function send($data, $code = 200)
    // Fonction permettant de convertir en fichier json le tableau de données avant l'envoi
    {
        if ($this->testing) return $data;
        http_response_code($code);
        header('content-type: application/json');
        echo json_encode($data); //data convertie en json
        die;
    }
}