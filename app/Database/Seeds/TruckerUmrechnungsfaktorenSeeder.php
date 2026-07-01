<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TruckerUmrechnungsfaktorenSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'trucker_id'          => 1,
                'cbm_faktor'          => 200,
                'ldm_faktor'          => 1000,
                'ldm_ab_europaletten' => 5,
                'created_at'          => date('Y-m-d H:i:s'),
                'updated_at'          => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('trucker_umrechnungsfaktoren')->insertBatch($data);
    }
}