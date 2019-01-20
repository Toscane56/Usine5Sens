<?php

namespace App\Controllers;

use App\Controller;

class UserController extends Controller
//classe des utilisateurs 
{
    const SALT = 'usine-5-sens'; //définition du "sel" pour crypter le mot de passe

    public function login()
    //fonction permettant la connexion de l'utilisateur
    {
        $email = $this->getValue('email'); //récupération de l'email de l'utilisateur
        $password = $this->getValue('password'); //récupération du mot de passe

        if (!is_null($email) && !is_null($password)) //si le mot de passe et l'email sont définis
        {
            $user = $this->db->fetch('users', ['email' => $email, 'password' => sha1(self::SALT.$password)]);
            //les valeurs de l'email et du mot de passe crypté sont comparées à celles présentes dans la bdd 
            if ($user)
            //si la vérification est un succès (l'email et le mot de passe existe dans la bdd)
            {
                $token = md5($user['email'].time());//création d'un token

                $this->db->update('users', ['id' => $user['id']], ['token' => $token]);

                $user['token'] = $token;
                unset($user['password']);

                return $this->success('You logged in!', [
                    'user' => $user //envoi un message du succès de la connexion
                ]);
            }
        }

        return $this->error("Ce compte n'existe pas."); //si la vérification echoue (le mail et le mot de passe n'existe pas dans la bdd) alors un message d'erreur est envoyé
    }

    public function register()
    //fonction permettant l'inscription d'un utilisateur
    {
        $email = $this->getValue('email'); //récupération de l'email
        $password = $this->getValue('password'); //récupération du mot de passe
        $firstname = $this->getValue('firstname'); //récupération du prénom
        $lastname = $this->getValue('lastname'); //récupération du nom

        if (!is_null($email) && !is_null($password) && !is_null($firstname) && !is_null($lastname))
        //si aucun des champs récupéré est nul
        {
            $user = $this->db->fetch('users', ['email' => $email]);
            //vérification que l'email n'est pas présente dans la base de données (clé unique)

            if ($user) {
                //si l'email existe dans la base de données (table user)
                $this->error("Cette adresse e-mail n'est pas disponible"); //envoi un message d'erreur
            }
            //sinon l'inscription de l'utilisateur se poursuit
            $token = md5($user['email'].time());

            $user = $this->db->insert('users', ['token' => $token, 'email' => $email, 'password' => sha1(self::SALT.$password), 'firstname' => $firstname, 'lastname' => $lastname]);
            unset($user['password']);//insertion du nouvel utilisateur dans la table user de la bdd

            return $this->success('Votre compte a été créé', [
                'user' => $user //envoi un message lorsque l'inscription est un succès
            ]);
        }

        return $this->error("Veuillez compléter le fomulaire."); //si au moins un des champs n'est pas rempli, envoi un message d'erreur
    }

    public function profile()
    //fonction permettant de récuperer le profil de l'utilisateur
    {
        $user = $this->getUser();
        if (isset($user['error'])) return $user; //envoi un message d'erreur en cas de problème avec la session

        unset($user['password']);
        unset($user['token']);

        return $this->success("User retrieved successfully", [
            'user' => $user
        ]);
    }

    public function update()
    //fonction permettant de mettre à jour le profil de l'utilisateur
    {
        $user = $this->getUser();
        if (isset($user['error'])) return $user;

        $email = $this->getValue('email'); //récupération de l'email
        $password = $this->getValue('password'); //récupération du mot de passe
        $firstname = $this->getValue('firstname'); //récupération du prénom
        $lastname = $this->getValue('lastname'); //récupération du nom

        if (!is_null($email) && !is_null($password) && !is_null($firstname) && !is_null($lastname))
        //si aucun des champs récupéré est nul
        {
            $email_user = $this->db->fetch('users', ['email' => $email]);
            //vérification que l'email n'est pas présente dans la base de données (clé unique) pour un autre utilisateur

            if ($email_user && $user['id'] != $email_user['id']) {
                //si un autre utilisateur a cette adresse email
                $this->error("Cette adresse e-mail n'est pas disponible");
                //envoi d'un message d'erreur
            }

            $user = $this->db->update('users', ['id' => $user['id']], ['email' => $email, 'password' => sha1(self::SALT.$password), 'firstname' => $firstname, 'lastname' => $lastname]); 
            //mise à jour du profile de l'utilisateur
            if (!$user) return $this->error('Failed to update'); //message d'erreur en cas d'echec de la mise à jour

            unset($user['token']);
            unset($user['password']);

            return $this->success('Mise à jour réussie', [
                'user' => $user //envoi un message lorsque la mise à jour est un succès
            ]);
        }

        return $this->error("Veuillez compléter le fomulaire."); //si au moins un des champs n'est pas rempli, envoi un message d'erreur
    }
}