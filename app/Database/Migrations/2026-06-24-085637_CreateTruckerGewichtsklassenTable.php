<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTruckerGewichtsklassenTable extends Migration
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
            'gewicht_bis' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,3',
                'null'       => false,
                'comment'    => 'Obergrenze der Gewichtsklasse in kg (z.B. 60, 80, 100 ...)',
            ],
            'gewicht_von' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,3',
                'null'       => false,
                'comment'    => 'Untergrenze der Gewichtsklasse in kg',
            ],
            'sortierung' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'default'    => 0,
                'comment'    => 'Anzeigereihenfolge',
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
        $this->forge->createTable('trucker_gewichtsklassen');
    }

    public function down()
    {
        $this->forge->dropTable('trucker_gewichtsklassen');
    }
}