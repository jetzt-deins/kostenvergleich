<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTruckerUmrechnungsfaktorenTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'trucker_id' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'cbm_faktor' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,4',
                'null'       => false,
                'comment'    => 'Gewicht = CBM x Faktor (z.B. 200)',
            ],
            'ldm_faktor' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,4',
                'null'       => false,
                'comment'    => 'Gewicht = LDM x Faktor (z.B. 1000)',
            ],
            'ldm_ab_europaletten' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'null'       => true,
                'comment'    => 'Ab x Europaletten wird LDM-Satz verwendet',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('trucker_id', 'trucker', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('trucker_umrechnungsfaktoren');
    }

    public function down()
    {
        $this->forge->dropTable('trucker_umrechnungsfaktoren');
    }
}