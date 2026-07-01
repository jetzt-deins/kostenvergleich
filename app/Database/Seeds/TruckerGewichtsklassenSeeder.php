<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TruckerGewichtsklassenSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['trucker_id' => 1, 'gewicht_von' => 0,       'gewicht_bis' => 60,   'sortierung' => 1,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 60.001,  'gewicht_bis' => 80,   'sortierung' => 2,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 80.001,  'gewicht_bis' => 100,  'sortierung' => 3,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 100.001, 'gewicht_bis' => 125,  'sortierung' => 4,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 125.001, 'gewicht_bis' => 150,  'sortierung' => 5,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 150.001, 'gewicht_bis' => 175,  'sortierung' => 6,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 175.001, 'gewicht_bis' => 200,  'sortierung' => 7,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 200.001, 'gewicht_bis' => 225,  'sortierung' => 8,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 225.001, 'gewicht_bis' => 250,  'sortierung' => 9,  'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 250.001, 'gewicht_bis' => 275,  'sortierung' => 10, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 275.001, 'gewicht_bis' => 300,  'sortierung' => 11, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 300.001, 'gewicht_bis' => 350,  'sortierung' => 12, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 350.001, 'gewicht_bis' => 400,  'sortierung' => 13, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 400.001, 'gewicht_bis' => 450,  'sortierung' => 14, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 450.001, 'gewicht_bis' => 500,  'sortierung' => 15, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 500.001, 'gewicht_bis' => 600,  'sortierung' => 16, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 600.001, 'gewicht_bis' => 700,  'sortierung' => 17, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 700.001, 'gewicht_bis' => 800,  'sortierung' => 18, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 800.001, 'gewicht_bis' => 900,  'sortierung' => 19, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 900.001, 'gewicht_bis' => 1000, 'sortierung' => 20, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 1000.001,'gewicht_bis' => 1100, 'sortierung' => 21, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 1100.001,'gewicht_bis' => 1200, 'sortierung' => 22, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 1200.001,'gewicht_bis' => 1300, 'sortierung' => 23, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 1300.001,'gewicht_bis' => 1400, 'sortierung' => 24, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 1400.001,'gewicht_bis' => 1500, 'sortierung' => 25, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 1500.001,'gewicht_bis' => 1600, 'sortierung' => 26, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 1600.001,'gewicht_bis' => 1700, 'sortierung' => 27, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 1700.001,'gewicht_bis' => 1800, 'sortierung' => 28, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 1800.001,'gewicht_bis' => 1900, 'sortierung' => 29, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 1900.001,'gewicht_bis' => 2000, 'sortierung' => 30, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 2000.001,'gewicht_bis' => 2250, 'sortierung' => 31, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 2250.001,'gewicht_bis' => 2500, 'sortierung' => 32, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 2500.001,'gewicht_bis' => 2750, 'sortierung' => 33, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['trucker_id' => 1, 'gewicht_von' => 2750.001,'gewicht_bis' => 3000, 'sortierung' => 34, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('trucker_gewichtsklassen')->insertBatch($data);
    }
}