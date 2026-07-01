<?php

namespace App\Controllers\Verwaltung;

use App\Controllers\BaseController;

class Preistabellen extends BaseController
{
    public function index(): string
    {
        $db      = \Config\Database::connect();
        $trucker = $db->table('trucker')->where('aktiv', 1)->orderBy('name')->get()->getResultArray();

        return view('verwaltung/preistabellen/index', [
            'title'   => 'Preistabellen',
            'trucker' => $trucker,
        ]);
    }

    public function anzeigen(int $trucker_id, string $richtung = 'distribution'): string
    {
        $db      = \Config\Database::connect();
        $trucker = $db->table('trucker')->where('id', $trucker_id)->get()->getRowArray();

        $gewichtsklassen = $db->table('trucker_gewichtsklassen')
            ->where('trucker_id', $trucker_id)
            ->orderBy('sortierung')
            ->get()->getResultArray();

        // Preise als 2D-Array [plz][gewichtsklassen_id] => preis
        $preise_raw = $db->table('preistabellen')
            ->where('trucker_id', $trucker_id)
            ->where('richtung', $richtung)
            ->get()->getResultArray();

        $preise = [];
        foreach ($preise_raw as $p) {
            $preise[$p['plz']][$p['gewichtsklassen_id']] = $p['preis'];
        }

        ksort($preise);

        return view('verwaltung/preistabellen/anzeigen', [
            'title'           => 'Preistabelle: ' . $trucker['name'] . ' (' . ucfirst($richtung) . ')',
            'trucker'         => $trucker,
            'richtung'        => $richtung,
            'gewichtsklassen' => $gewichtsklassen,
            'preise'          => $preise,
        ]);
    }

    public function bearbeiten(int $trucker_id, string $richtung, string $plz): string
    {
        $db      = \Config\Database::connect();
        $trucker = $db->table('trucker')->where('id', $trucker_id)->get()->getRowArray();

        $gewichtsklassen = $db->table('trucker_gewichtsklassen')
            ->where('trucker_id', $trucker_id)
            ->orderBy('sortierung')
            ->get()->getResultArray();

        $preise_raw = $db->table('preistabellen')
            ->where('trucker_id', $trucker_id)
            ->where('richtung', $richtung)
            ->where('plz', $plz)
            ->get()->getResultArray();

        $preise = [];
        foreach ($preise_raw as $p) {
            $preise[$p['gewichtsklassen_id']] = ['id' => $p['id'], 'preis' => $p['preis']];
        }

        return view('verwaltung/preistabellen/bearbeiten', [
            'title'           => 'Preise bearbeiten: PLZ ' . $plz . ' (' . ucfirst($richtung) . ')',
            'trucker'         => $trucker,
            'richtung'        => $richtung,
            'plz'             => $plz,
            'gewichtsklassen' => $gewichtsklassen,
            'preise'          => $preise,
        ]);
    }

public function aktualisieren(int $trucker_id, string $richtung, string $plz)
    {
        $db     = \Config\Database::connect();
        $preise = $this->request->getPost('preise');

        // Falls neue PLZ-Zone, PLZ aus Formular nehmen
        if (!$plz || $plz === '0') {
            $plz = $this->request->getPost('plz_neu');
        }

        foreach ($preise as $gewichtsklassen_id => $eintrag) {
            if (empty($eintrag['preis'])) continue;

            if (isset($eintrag['id']) && $eintrag['id']) {
                $db->table('preistabellen')->where('id', $eintrag['id'])->update([
                    'preis'      => $eintrag['preis'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            } else {
                // Prüfen ob bereits vorhanden
                $existing = $db->table('preistabellen')
                    ->where('trucker_id', $trucker_id)
                    ->where('richtung', $richtung)
                    ->where('plz', $plz)
                    ->where('gewichtsklassen_id', $gewichtsklassen_id)
                    ->get()->getRowArray();

                if ($existing) {
                    $db->table('preistabellen')->where('id', $existing['id'])->update([
                        'preis'      => $eintrag['preis'],
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                } else {
                    $db->table('preistabellen')->insert([
                        'trucker_id'         => $trucker_id,
                        'richtung'           => $richtung,
                        'plz'                => $plz,
                        'gewichtsklassen_id' => $gewichtsklassen_id,
                        'preis'              => $eintrag['preis'],
                        'created_at'         => date('Y-m-d H:i:s'),
                        'updated_at'         => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }

        session()->setFlashdata('success', 'Preise für PLZ ' . $plz . ' wurden erfolgreich gespeichert.');
        return redirect()->to(base_url('verwaltung/preistabellen/anzeigen/' . $trucker_id . '/' . $richtung));
    }

    public function plzNeu(int $trucker_id, string $richtung): string
    {
        $db      = \Config\Database::connect();
        $trucker = $db->table('trucker')->where('id', $trucker_id)->get()->getRowArray();

        $gewichtsklassen = $db->table('trucker_gewichtsklassen')
            ->where('trucker_id', $trucker_id)
            ->orderBy('sortierung')
            ->get()->getResultArray();

        return view('verwaltung/preistabellen/bearbeiten', [
            'title'           => 'Neue PLZ-Zone anlegen',
            'trucker'         => $trucker,
            'richtung'        => $richtung,
            'plz'             => '',
            'gewichtsklassen' => $gewichtsklassen,
            'preise'          => [],
        ]);
    }
}