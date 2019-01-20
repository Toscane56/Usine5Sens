<?php

namespace App\Controllers;

use App\Controller;

class WorkshopController extends Controller
//classe pour la gestion des ateliers
{
    public function index()
    //fonction permettant de montrer tous les ateliers et les sens associés de la journée
    {
        $query = $this->db->query("SELECT * FROM workshops WHERE starting_at <= NOW() AND ending_at >= NOW() ORDER BY scheduled_at ASC");
        //préparation de la requete qui selectionne tous les ateliers de la journée
        $query->execute(); //envoi de la requete

        $workshops = $query->fetchAll(); //récupération de tous les ateliers dans un tableau

        foreach ($workshops as $i => $workshop)
        //pour chaque atelier
        {
            $query = $this->db->query('SELECT * FROM senses s WHERE s.id IN (SELECT sense_id FROM workshop_senses ws WHERE ws.workshop_id = ?)');
            //récupération des sens associés à l'atelier
            $query->execute([$workshop['id']]);
            $workshops[$i]['senses'] = $query->fetchAll();
        }

        return $this->success('Workshops of the day', [
            'workshops' => $workshops
        ]);
    }

    public function reservations()
    //fonction permmetant de voir les réservations d'un utilisateur
    {
        $user = $this->getUser();
        if (isset($user['error'])) return $user; //si l'utilisateur n'est pas connecté, envoi d'un message d'erreur

        $reservations = $this->db->fetchAll('workshop_reservations', ['user_id' => $user['id']]);
        //récupération de toutes les réservations de l'utilisateur
        $workshops = [];

        foreach ($reservations as $i => $reservation)
        {
            $workshop = $this->db->fetch('workshops', ['id' => $reservation['workshop_id']]);
            if (strtotime($workshop['ending_at']) >= time() || $workshop['ending_at'] == date('Y-m-d')) {
                $workshop['date'] = $reservation['date'];
                $workshops[] = $workshop;
            }
        }

        return $this->success('Reservations retrieved', [
            'workshops' => $workshops
        ]);
    }

    public function book()
    //fonction permettant de réserver un atelier pour un utilisateur déjà connecté
    {
        $user = $this->getUser();
        if (isset($user['error'])) return $user;  //si l'utilisateur n'est pas connecté, envoi d'un message d'erreur

        $workshop_id = $this->getValue('workshop_id'); //récupération de l'id de l'atelier dans l'envoi de la requete POST
        if (is_null($workshop_id)) return $this->error('Workshop id required');  //s'il n'y a pas l'ID de l'atelier dans l'envoi de la requete POST, envoi d'un message d'erreur

        $workshop = $this->db->fetch('workshops', ['id' => $workshop_id]); //vérification que l'id correspond à un id d'un atelier dans la base de données
        if (!$workshop) return $this->error('Workshop not found'); //si la vérification échoue, envoi d'un message d'erreur

        if (strtotime($workshop['ending_at']) < time() && $workshop['ending_at'] != date('Y-m-d')) {
        //si l'utilisateur essaye de réserver un atelier qui ne se produit pas aujourd'hui
            return $this->error("You cannot book this workshop."); //envoie d'un message d'erreur
        }

        $reservation = $this->db->fetch('workshop_reservations', ['workshop_id' => $workshop_id, 'user_id' => $user['id']]);
        if ($reservation) return $this->error('Workshop already booked'); //si l'utilisateur essaye de réserver un atelier qu'il a déjà reservé, envoi d'un message d'erreur

        $reservation = $this->db->insert('workshop_reservations', [
            //création de la réservation dans la base de données
            'workshop_id' => $workshop_id,
            'user_id' => $user['id'],
            'date' => date('Y-m-d')
        ]);

        return $this->success('Workshop booked', [
            //réservation effectuée
            'workshop' => $workshop,
            'reservation' => $reservation
        ]);
    }

    public function remove()
    //fonction permettant à un utilisateur de se désinscrire
    {
        $user = $this->getUser();
        if (isset($user['error'])) return $user;  //si l'utilisateur n'est pas connecté, envoi d'un message d'erreur

        $workshop_id = $this->getValue('workshop_id'); //récupération de l'id de l'atelier dans l'envoi de la requete POST
        if (is_null($workshop_id)) return $this->error('Workshop id required'); //s'il n'y a pas l'ID de l'atelier dans l'envoi de la requete POST, envoi d'un message d'erreur

        $workshop = $this->db->fetch('workshops', ['id' => $workshop_id]); //vérification que l'id correspond à un id d'un atelier dans la base de données
        if (!$workshop) return $this->error('Workshop not found'); //si la vérification échoue, envoi d'un message d'erreur

        $reservation = $this->db->fetch('workshop_reservations', ['workshop_id' => $workshop_id, 'user_id' => $user['id']]); //vérification que la réservation de l'atelier par l'utilisateur existe
        if (!$reservation) return $this->error('You have not book this workshop'); //si la réservation n'existe pas, envoi d'une message d'erreur

        $this->db->delete('workshop_reservations', ['id' => $reservation['id']]); //suppression de la réservation

        return $this->success('Reservation deleted'); //suppression effectuée
    }
}