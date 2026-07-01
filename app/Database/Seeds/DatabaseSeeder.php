<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(TruckerSeeder::class);
        $this->call(TruckerUmrechnungsfaktorenSeeder::class);
        $this->call(TruckerVerpackungsartenSeeder::class);
        $this->call(TruckerZusatzprodukteSeeder::class);
        $this->call(TruckerGewichtsklassenSeeder::class);
        $this->call(PreistabellenSeeder::class);
    }
}