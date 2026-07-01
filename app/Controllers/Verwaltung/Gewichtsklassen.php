<?php

namespace App\Controllers\Verwaltung;

use App\Controllers\BaseController;

class Gewichtsklassen extends BaseController
{
    public function index(int $trucker_id): string
    {
        $db      = \Config\Database::connect();
        $trucker = $db->table('trucker')->where('id', $trucker_id)->get()->getRowArray();
        $klassen = $db->table('trucker_gewichtsklassen')
            ->where('trucker_id', $trucker_id)
            ->orderBy('sortierung')
            ->get()->getResultArray();

        return view('verwaltung/gewichtsklassen/index', [
            'title'   => 'Gewichtsklassen: ' . $trucker['name'],
            'trucker' => $trucker,
            'klassen' => $klassen,
        ]);
    }

    public function neu(int $trucker_id): string
    {
        $db      = \Config\Database::connect();
        $trucker = $db->table('trucker')->where('id', $trucker_id)->get()->getRowArray();

        return view('verwaltung/gewichtsklassen/form', [
            'title'   => 'Neue Gewichtsklasse',
            'trucker' => $trucker,
            'eintrag' => null,
        ]);
    }

    public function speichern()
    {
        $db         = \Config\Database::connect();
        $trucker_id = $this->request->getPost('trucker_id');

        // Höchste Sortierung ermitteln
        $max = $db->table('trucker_gewichtsklassen')
            ->where('trucker_id', $trucker_id)
            ->selectMax('sortierung')
            ->get()->getRowArray();

        $db->table('trucker_gewichtsklassen')->insert([
            'trucker_id'  => $trucker_id,
            'gewicht_von' => $this->request->getPost('gewicht_von'),
            'gewicht_bis' => $this->request->getPost('gewicht_bis'),
            'sortierung'  => ($max['sortierung'] ?? 0) + 1,
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        session()->setFlashdata('success', 'Gewichtsklasse wurde erfolgreich angelegt.');
        return redirect()->to(base_url('verwaltung/gewichtsklassen/' . $trucker_id));
    }

    public function bearbeiten(int $id): string
    {
        $db      = \Config\Database::connect();
        $eintrag = $db->table('trucker_gewichtsklassen')->where('id', $id)->get()->getRowArray();
        $trucker = $db->table('trucker')->where('id', $eintrag['trucker_id'])->get()->getRowArray();

        return view('verwaltung/gewichtsklassen/form', [
            'title'   => 'Gewichtsklasse bearbeiten',
            'trucker' => $trucker,
            'eintrag' => $eintrag,
        ]);
    }

    public function aktualisieren(int $id)
    {
        $db      = \Config\Database::connect();
        $eintrag = $db->table('trucker_gewichtsklassen')->where('id', $id)->get()->getRowArray();

        $db->table('trucker_gewichtsklassen')->where('id', $id)->update([
            'gewicht_von' => $this->request->getPost('gewicht_von'),
            'gewicht_bis' => $this->request->getPost('gewicht_bis'),
            'sortierung'  => $this->request->getPost('sortierung'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        session()->setFlashdata('success', 'Gewichtsklasse wurde erfolgreich aktualisiert.');
        return redirect()->to(base_url('verwaltung/gewichtsklassen/' . $eintrag['trucker_id']));
    }

    public function loeschen(int $id)
    {
        $db      = \Config\Database::connect();
        $eintrag = $db->table('trucker_gewichtsklassen')->where('id', $id)->get()->getRowArray();
        $db->table('trucker_gewichtsklassen')->where('id', $id)->delete();
        session()->setFlashdata('success', 'Gewichtsklasse wurde erfolgreich gelöscht.');
        return redirect()->to(base_url('verwaltung/gewichtsklassen/' . $eintrag['trucker_id']));
    }
}