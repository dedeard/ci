<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePatientHistoryTable extends Migration
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
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'date_visit' => [
                'type' => 'DATETIME',
            ],
            'registered_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'consultation_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'symptoms' => [
                'type' => 'TEXT',
            ],
            'doctor_diagnose' => [
                'type' => 'TEXT',
            ],
            'icd10_code' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'icd10_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'is_done' => [
                'type' => 'BOOLEAN',
                'default' => false,
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
        $this->forge->addForeignKey('record_number', 'patients', 'record_number', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('registered_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('consultation_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('patient_histories');
    }

    public function down()
    {
        $this->forge->dropTable('patient_histories');
    }
}