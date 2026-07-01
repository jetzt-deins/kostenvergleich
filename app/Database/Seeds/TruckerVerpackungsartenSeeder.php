<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TruckerVerpackungsartenSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'trucker_id'        => 1,
                'bezeichnung'       => 'Europalette',
                'min_gewicht'       => 200,
                'max_gewicht'       => 400,
                'max_laenge'        => null,
                'max_breite'        => null,
                'max_hoehe'         => null,
                'max_gewicht_kolli' => 1500,
                'sortierung'        => 1,
                'aktiv'             => 1,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'trucker_id'        => 1,
                'bezeichnung'       => 'Industriepalette',
                'min_gewicht'       => 250,
                'max_gewicht'       => null,
                'max_laenge'        => null,
                'max_breite'        => null,
                'max_hoehe'         => null,
                'max_gewicht_kolli' => 1500,
                'sortierung'        => 2,
                'aktiv'             => 1,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'trucker_id'        => 1,
                'bezeichnung'       => 'Gitterbox',
                'min_gewicht'       => 200,
                'max_gewicht'       => 400,
                'max_laenge'        => null,
                'max_breite'        => null,
                'max_hoehe'         => null,
                'max_gewicht_kolli' => 1500,
                'sortierung'        => 3,
                'aktiv'             => 1,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'trucker_id'        => 1,
                'bezeichnung'       => 'Halbpalette',
                'min_gewicht'       => 100,
                'max_gewicht'       => 400,
                'max_laenge'        => null,
                'max_breite'        => null,
                'max_hoehe'         => null,
                'max_gewicht_kolli' => 1500,
                'sortierung'        => 4,
                'aktiv'             => 1,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'trucker_id'        => 1,
                'bezeichnung'       => 'Viertelpalette',
                'min_gewicht'       => 50,
                'max_gewicht'       => 400,
                'max_laenge'        => null,
                'max_breite'        => null,
                'max_hoehe'         => null,
                'max_gewicht_kolli' => 1500,
                'sortierung'        => 5,
                'aktiv'             => 1,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'trucker_id'        => 1,
                'bezeichnung'       => 'Einwegpalette',
                'min_gewicht'       => 0,
                'max_gewicht'       => 0,
                'max_laenge'        => null,
                'max_breite'        => null,
                'max_hoehe'         => null,
                'max_gewicht_kolli' => 1500,
                'sortierung'        => 6,
                'aktiv'             => 1,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'trucker_id'        => 1,
                'bezeichnung'       => 'Unpalettierte VPE',
                'min_gewicht'       => 0,
                'max_gewicht'       => null,
                'max_laenge'        => null,
                'max_breite'        => null,
                'max_hoehe'         => null,
                'max_gewicht_kolli' => 1500,
                'sortierung'        => 7,
                'aktiv'             => 1,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('trucker_verpackungsarten')->insertBatch($data);
    }
}