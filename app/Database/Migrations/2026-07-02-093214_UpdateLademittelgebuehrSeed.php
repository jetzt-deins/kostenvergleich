<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateLademittelgebuehrSeed extends Migration
{
    public function up()
    {
        // Europalette: 0,00 € (Tausch - oft kostenlos oder separat)
        $this->db->table('trucker_verpackungsarten')
            ->where('trucker_id', 1)
            ->where('bezeichnung', 'Europalette')
            ->update([
                'lademittelgebuehr'          => 0.00,
                'lademittelgebuehr_standard' => 1,
            ]);

        // Gitterbox: Gebühr je nach Trucker
        $this->db->table('trucker_verpackungsarten')
            ->where('trucker_id', 1)
            ->where('bezeichnung', 'Gitterbox')
            ->update([
                'lademittelgebuehr'          => 5.00,
                'lademittelgebuehr_standard' => 1,
            ]);
    }

    public function down()
    {
        $this->db->table('trucker_verpackungsarten')
            ->where('trucker_id', 1)
            ->whereIn('bezeichnung', ['Europalette', 'Gitterbox'])
            ->update([
                'lademittelgebuehr'          => null,
                'lademittelgebuehr_standard' => 1,
            ]);
    }
}