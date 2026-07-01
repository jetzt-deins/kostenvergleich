<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TruckerZusatzprodukteSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'trucker_id'    => 1,
                'bezeichnung'   => 'kein',
                'aufschlag'     => 0,
                'aufschlag_typ' => 'fix',
                'sortierung'    => 0,
                'aktiv'         => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'trucker_id'    => 1,
                'bezeichnung'   => 'KJ - Explosive',
                'aufschlag'     => 12.50,
                'aufschlag_typ' => 'fix',
                'sortierung'    => 1,
                'aktiv'         => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'trucker_id'    => 1,
                'bezeichnung'   => 'Avis',
                'aufschlag'     => 6.50,
                'aufschlag_typ' => 'fix',
                'sortierung'    => 2,
                'aktiv'         => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'trucker_id'    => 1,
                'bezeichnung'   => 'Privatempfänger',
                'aufschlag'     => 5.15,
                'aufschlag_typ' => 'fix',
                'sortierung'    => 3,
                'aktiv'         => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'trucker_id'    => 1,
                'bezeichnung'   => 'Privat & Avis',
                'aufschlag'     => 11.65,
                'aufschlag_typ' => 'fix',
                'sortierung'    => 4,
                'aktiv'         => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'trucker_id'    => 1,
                'bezeichnung'   => 'KJ - NextDay 17',
                'aufschlag'     => 25.00,
                'aufschlag_typ' => 'fix',
                'sortierung'    => 5,
                'aktiv'         => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'trucker_id'    => 1,
                'bezeichnung'   => 'KJ - NextDay 12',
                'aufschlag'     => 37.50,
                'aufschlag_typ' => 'fix',
                'sortierung'    => 6,
                'aktiv'         => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'trucker_id'    => 1,
                'bezeichnung'   => 'KJ - NextDay 10',
                'aufschlag'     => 45.00,
                'aufschlag_typ' => 'fix',
                'sortierung'    => 7,
                'aktiv'         => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'trucker_id'    => 1,
                'bezeichnung'   => 'KJ - Fix-Termin 17',
                'aufschlag'     => 18.00,
                'aufschlag_typ' => 'fix',
                'sortierung'    => 8,
                'aktiv'         => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'trucker_id'    => 1,
                'bezeichnung'   => 'KJ - Fix-Termin 12',
                'aufschlag'     => 32.00,
                'aufschlag_typ' => 'fix',
                'sortierung'    => 9,
                'aktiv'         => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'trucker_id'    => 1,
                'bezeichnung'   => 'KJ - Fix-Termin 10',
                'aufschlag'     => 39.00,
                'aufschlag_typ' => 'fix',
                'sortierung'    => 10,
                'aktiv'         => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('trucker_zusatzprodukte')->insertBatch($data);
    }
}