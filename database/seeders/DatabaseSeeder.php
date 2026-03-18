<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /*  User::factory()->create([
              'name' => 'Test User',
              'email' => 'test@example.com',
          ]); */

        \App\Models\User::create([
            'name' => 'SAO Admin',
            'email' => 'admin@ctu.edu.ph',
            'password' => bcrypt('password123'),
            'role' => 3,
            'rfid_number' => 12345678,
        ]);

        // Create a Campus Safety Officer (Role 2)
        \App\Models\User::create([
            'name' => 'CSO Officer',
            'email' => 'cso@ctu.edu.ph',
            'password' => bcrypt('password123'),
            'role' => 2,
            'rfid_number' => 123456789,
        ]);

                // 1. Seed Departments FIRST so they have IDs 1-4
        $this->call(DepartmentSeeder::class);

        // 2. Now seed users who belong to those departments
        \App\Models\User::factory(100)->create();

        // Create a Sample Student (Role 1)
        \App\Models\User::create([
            'name' => 'John Carlo C. Arias',
            'email' => 'student@ctu.edu.ph',
            'password' => bcrypt('password123'),
            'role' => 1,
            'rfid_number' => 61300211875,
            'year_level' => 3,
            'course' => 'BSIT',
            'id_number' => 3231028,
            'department_id' => 4,
            //cot = 1 ceas = 2 cme = 3 cot = 4
        ]);

    }
}
