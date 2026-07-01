<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TruckerSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id'         => 1,
                'name'       => 'Karl Jürgensen',
                'kurzname'   => 'KJ',
                'strasse'    => '',
                'plz'        => '20457',
                'ort'        => 'Hamburg',
                'telefon'    => '040 / 2533643 - 06',
                'email'      => '',
                'aktiv'      => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('trucker')->insertBatch($data);
    }
}