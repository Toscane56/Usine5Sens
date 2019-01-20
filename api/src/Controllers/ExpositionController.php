<?php

namespace App\Controllers;

use App\Controller;

class ExpositionController extends Controller
//classe pour les expositions 
{
    public function index()
    //fonction permettant de récupérer toutes les expositions de la journée en cours
    {
        $query = $this->db->query("SELECT * FROM expositions WHERE starting_at <= NOW() AND ending_at >= NOW()");
        //requete permettant la sélection de toutes les expositions de la journée en cours
        $query->execute(); //envoi de la requete
        $exposition = $query->fetch();

        if ($exposition) {
            return $this->success('Exposition found', [
                // si une exposition est trouvée
                'exposition' => $exposition //récupération de l'exposition trouvée
            ]);
        }
        else {
            return $this->error("No exposition today"); //si la requete ne trouve pas d'exposition, un message d'erreur est envoyé 
        }
    }
}