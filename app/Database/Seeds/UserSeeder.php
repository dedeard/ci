<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'email'    => 'admin@simawi.com',
                'password' => password_hash('admin123', PASSWORD_BCRYPT),
                'name'     => 'System Administrator',
                'role'     => 'Admin',
            ],
            [
                'email'    => 'doctor@simawi.com',
                'password' => password_hash('doctor123', PASSWORD_BCRYPT),
                'name'     => 'Dr. John Doe',
                'role'     => 'Doctor',
            ],
            [
                'email'    => 'jane@simawi.com',
                'password' => password_hash('doctor123', PASSWORD_BCRYPT),
                'name'     => 'Dr. Jane Smith',
                'role'     => 'Doctor',
            ]
        ];

        // Using Query Builder
        $this->db->table('users')->insertBatch($data);
    }
}
