<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLademittelgebuehrToVerpackungsarten extends Migration
{
    public function up()
    {
        $this->forge->addColumn('trucker_verpackungsarten', [
            'lademittelgebuehr' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,4',
                'null'       => true,
                'default'    => null,
                'after'      => 'max_gewicht_kolli',
                'comment'    => 'Lademittelgebühr pro Stück in EUR',
            ],
            'lademittelgebuehr_standard' => [
                'type'    => 'INTEGER',
                'default' => 1,
                'after'   => 'lademittelgebuehr',
                'comment' => '1 = Checkbox standardmäßig aktiviert',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('trucker_verpackungsarten', 'lademittelgebuehr');
        $this->forge->dropColumn('trucker_verpackungsarten', 'lademittelgebuehr_standard');
    }
}