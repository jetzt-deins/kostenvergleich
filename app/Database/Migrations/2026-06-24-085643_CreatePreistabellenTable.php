<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePreistabellenTable extends Migration
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
            'richtung' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => false,
                'comment'    => 'distribution oder beschaffung',
            ],
            'plz' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'null'       => false,
                'comment'    => 'Zweistellige PLZ-Zone (1-99)',
            ],
            'gewichtsklassen_id' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'preis' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,4',
                'null'       => false,
                'comment'    => 'Frachtpreis in EUR',
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
        $this->forge->addForeignKey('gewichtsklassen_id', 'trucker_gewichtsklassen', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('preistabellen');
    }

    public function down()
    {
        $this->forge->dropTable('preistabellen');
    }
}