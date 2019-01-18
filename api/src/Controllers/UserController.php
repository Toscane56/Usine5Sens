<?php

namespace App\Controllers;

use App\Controller;

class UserController extends Controller
{
    const SALT = 'usine-5-sens';

    public function login()
    {
        $email = $this->getValue('email');
        $password = $this->getValue('password');

        if (!is_null($email) && !is_null($password))
        {
            $user = $this->db->fetch('users', ['email' => $email, 'password' => sha1(self::SALT.$password)]);

            if ($user)
            {
                $token = md5($user['email'].time());

                $this->db->update('users', ['id' => $user['id']], ['token' => $token]);

                $user['token'] = $token;
                unset($user['password']);

                return $this->success('You logged in!', [
                    'user' => $user
                ]);
            }
        }

        return $this->error("Ce compte n'existe pas.");
    }

    public function register()
    {
        $email = $this->getValue('email');
        $password = $this->getValue('password');
        $firstname = $this->getValue('firstname');
        $lastname = $this->getValue('lastname');

        if (!is_null($email) && !is_null($password) && !is_null($firstname) && !is_null($lastname))
        {
            $user = $this->db->fetch('users', ['email' => $email]);

            if ($user) {
                $this->error("Cette adresse e-mail n'est pas disponible");
            }

            $token = md5($user['email'].time());

            $user = $this->db->insert('users', ['token' => $token, 'email' => $email, 'password' => sha1(self::SALT.$password), 'firstname' => $firstname, 'lastname' => $lastname]);
            unset($user['password']);

            return $this->success('Votre compte a été créé', [
                'user' => $user
            ]);
        }

        return $this->error("Veuillez compléter le fomulaire.");
    }

    public function profile()
    {
        $user = $this->getUser();
        if (isset($user['error'])) return $user;

        unset($user['password']);
        unset($user['token']);

        return $this->success("User retrieved successfully", [
            'user' => $user
        ]);
    }

    public function update()
    {
        $user = $this->getUser();
        if (isset($user['error'])) return $user;

        $email = $this->getValue('email');
        $password = $this->getValue('password');
        $firstname = $this->getValue('firstname');
        $lastname = $this->getValue('lastname');

        if (!is_null($email) && !is_null($password) && !is_null($firstname) && !is_null($lastname))
        {
            $email_user = $this->db->fetch('users', ['email' => $email]);

            if ($email_user && $user['id'] != $email_user['id']) {
                $this->error("Cette adresse e-mail n'est pas disponible");
            }

            $user = $this->db->update('users', ['id' => $user['id']], ['email' => $email, 'password' => sha1(self::SALT.$password), 'firstname' => $firstname, 'lastname' => $lastname]);
            if (!$user) return $this->error('Failed to update');

            unset($user['token']);
            unset($user['password']);

            return $this->success('Mise à jour réussie', [
                'user' => $user
            ]);
        }

        return $this->error("Veuillez compléter le fomulaire.");
    }
}