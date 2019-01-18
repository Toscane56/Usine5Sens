<?php

namespace App\Controllers;

use App\Controller;

class WorkshopController extends Controller
{
    public function index()
    {
        $query = $this->db->query("SELECT * FROM workshops WHERE starting_at <= NOW() AND ending_at >= NOW() ORDER BY scheduled_at ASC");
        $query->execute();

        $workshops = $query->fetchAll();

        foreach ($workshops as $i => $workshop)
        {
            $query = $this->db->query('SELECT * FROM senses s WHERE s.id IN (SELECT sense_id FROM workshop_senses ws WHERE ws.workshop_id = ?)');
            $query->execute([$workshop['id']]);
            $workshops[$i]['senses'] = $query->fetchAll();
        }

        return $this->success('Workshops of the day', [
            'workshops' => $workshops
        ]);
    }

    public function reservations()
    {
        $user = $this->getUser();
        if (isset($user['error'])) return $user;

        $reservations = $this->db->fetchAll('workshop_reservations', ['user_id' => $user['id']]);
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
    {
        $user = $this->getUser();
        if (isset($user['error'])) return $user;

        $workshop_id = $this->getValue('workshop_id');
        if (is_null($workshop_id)) return $this->error('Workshop id required');

        $workshop = $this->db->fetch('workshops', ['id' => $workshop_id]);
        if (!$workshop) return $this->error('Workshop not found');

        if (strtotime($workshop['ending_at']) < time() && $workshop['ending_at'] != date('Y-m-d')) {
            return $this->error("You cannot book this workshop.");
        }

        $reservation = $this->db->fetch('workshop_reservations', ['workshop_id' => $workshop_id, 'user_id' => $user['id']]);
        if ($reservation) return $this->error('Workshop already booked');

        $reservation = $this->db->insert('workshop_reservations', [
            'workshop_id' => $workshop_id,
            'user_id' => $user['id'],
            'date' => date('Y-m-d')
        ]);

        return $this->success('Workshop booked', [
            'workshop' => $workshop,
            'reservation' => $reservation
        ]);
    }

    public function remove()
    {
        $user = $this->getUser();
        if (isset($user['error'])) return $user;

        $workshop_id = $this->getValue('workshop_id');
        if (is_null($workshop_id)) return $this->error('Workshop id required');

        $workshop = $this->db->fetch('workshops', ['id' => $workshop_id]);
        if (!$workshop) return $this->error('Workshop not found');

        $reservation = $this->db->fetch('workshop_reservations', ['workshop_id' => $workshop_id, 'user_id' => $user['id']]);
        if (!$reservation) return $this->error('You have not book this workshop');

        $this->db->delete('workshop_reservations', ['id' => $reservation['id']]);

        return $this->success('Reservation deleted');
    }
}