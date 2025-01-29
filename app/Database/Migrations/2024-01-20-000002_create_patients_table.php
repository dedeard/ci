<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePatientsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'record_number' => [
                'type' => 'INT',
                'constraint' => 11,
                'unique' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'birth' => [
                'type' => 'DATE',
            ],
            'nik' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'address' => [
                'type' => 'TEXT',
            ],
            'blood_type' => [
                'type' => 'ENUM',
                'constraint' => ['A', 'B', 'AB', 'O'],
            ],
            'weight' => [
                'type' => 'FLOAT',
            ],
            'height' => [
                'type' => 'FLOAT',
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

        $this->forge->addKey('id', true);
        $this->forge->createTable('patients');
    }

    public function down()
    {
        $this->forge->dropTable('patients');
    }
}
