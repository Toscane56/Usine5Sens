<?php

namespace App\Controllers;

use App\Controller;

class ExpositionController extends Controller
{
    public function index()
    {
        $today = date('Y-m-d');
        $exposition = $this->db->fetch('expositions', ['date' => $today]);

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