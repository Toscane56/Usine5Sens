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
    {
        $token = $this->getToken();
        if (empty($token)) return $this->error('Invalid token');

        $user = $this->db->fetch('users', ['token' => $token]);
        if (!$user) return $this->error('Invalid token');

        return $user;
    }

    // Responses

    public function success($message, $data = [])
    {
        return $this->send(array_merge(['status' => 'success', 'message' => $message], $data));
    }

    public function error($message, $data = [], $code = 400)
    {
        return $this->send(array_merge(['status' => 'error', 'message' => $message], $data), $code);
    }

    public function send($data, $code = 200)
    {
        if ($this->testing) return $data;
        http_response_code($code);
        header('content-type: application/json');
        echo json_encode($data);
        die;
    }
}