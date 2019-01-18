<?php

namespace App\Controllers;

use App\Controller;

class ExpositionController extends Controller
{
    public function index()
    {
        $query = $this->db->query("SELECT * FROM expositions WHERE starting_at <= NOW() AND ending_at >= NOW()");
        $query->execute();
        $exposition = $query->fetch();

        if ($exposition) {
            return $this->success('Exposition found', [
                'exposition' => $exposition
            ]);
        }
        else {
            return $this->error("No exposition today");
        }
    }
}