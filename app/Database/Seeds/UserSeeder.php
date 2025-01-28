<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'email'    => 'admin@simawi.com',
            'password' => password_hash('admin123', PASSWORD_BCRYPT),
            'name'     => 'System Administrator',
            'role'     => 'Admin',
        ];

        // Using Query Builder
        $this->db->table('users')->insert($data);
    }
}