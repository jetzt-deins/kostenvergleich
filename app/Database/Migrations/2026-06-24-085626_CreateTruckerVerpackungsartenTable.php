<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTruckerVerpackungsartenTable extends Migration
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
            'bezeichnung' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
                'comment'    => 'z.B. Europalette, Gitterbox, Halbpalette etc.',
            ],
            'min_gewicht' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,3',
                'null'       => true,
                'comment'    => 'Mindestgewicht in kg',
            ],
            'max_gewicht' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,3',
                'null'       => true,
                'comment'    => 'Maximalgewicht in kg, NULL = keine Deckelung',
            ],
            'max_laenge' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
                'comment'    => 'Maximale Länge in cm',
            ],
            'max_breite' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
                'comment'    => 'Maximale Breite in cm',
            ],
            'max_hoehe' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
                'comment'    => 'Maximale Höhe in cm',
            ],
            'max_gewicht_kolli' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
                'comment'    => 'Maximalgewicht je Kolli in kg',
            ],
            'sortierung' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'default'    => 0,
                'comment'    => 'Anzeigereihenfolge',
            ],
            'aktiv' => [
                'type'    => 'INTEGER',
                'default' => 1,
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
        $this->forge->createTable('trucker_verpackungsarten');
    }

    public function down()
    {
        $this->forge->dropTable('trucker_verpackungsarten');
    }
}