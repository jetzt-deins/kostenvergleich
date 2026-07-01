<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTruckerZusatzprodukteTable extends Migration
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
                'comment'    => 'z.B. Avis, Privatempfänger, KJ-NextDay 17 etc.',
            ],
            'aufschlag' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,4',
                'null'       => false,
                'comment'    => 'Aufschlag in EUR',
            ],
            'aufschlag_typ' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'fix',
                'comment'    => 'fix = fixer Betrag, prozent = prozentualer Aufschlag',
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
        $this->forge->createTable('trucker_zusatzprodukte');
    }

    public function down()
    {
        $this->forge->dropTable('trucker_zusatzprodukte');
    }
}