<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class PatientSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('id_ID'); // Use Indonesian locale for realistic data
        $patients = [];

        for ($i = 1; $i <= 100; $i++) {
            $patients[] = [
                'record_number' => $i, // Auto-incremented record number
                'name' => $faker->name,
                'birth' => $faker->date('Y-m-d', '-20 years'), // Random birth date (20+ years old)
                'nik' => $faker->unique()->numerify('################'), // 16-digit NIK
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'blood_type' => $faker->randomElement(['A', 'B', 'AB', 'O']),
                'weight' => $faker->randomFloat(2, 40, 120), // Weight between 40kg and 120kg
                'height' => $faker->randomFloat(2, 140, 200), // Height between 140cm and 200cm
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        // Insert data into the `patients` table
        $this->db->table('patients')->insertBatch($patients);
    }
}
