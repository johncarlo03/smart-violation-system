<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;

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

        User::create([
            'name' => 'SAO Admin',
            'email' => 'admin@ctu.edu.ph',
            'password' => bcrypt('password123'),
            'role' => 3,
            'rfid_number' => 12345678,
        ]);

        User::create([
            'name' => 'SuperAdmin',
            'email' => 'superadmin@ctu.edu.ph',
            'password' => bcrypt('password123'),
            'role' => 4,
            'rfid_number' => 123456780,
        ]);

        // Create a Campus Safety Officer (Role 2)
        User::create([
            'name' => 'CSO Officer',
            'email' => 'cso@ctu.edu.ph',
            'password' => bcrypt('password123'),
            'role' => 2,
            'rfid_number' => 123456789,
        ]);

                // 1. Seed Departments FIRST so they have IDs 1-4
        $this->call(DepartmentSeeder::class);
        $this->call(CourseSeeder::class);

        // 2. Now seed users who belong to those departments
        User::factory(100)->create();

       
        User::create([
            'name' => 'John Carlo C. Arias',
            'email' => 'student@ctu.edu.ph',
            'password' => bcrypt('password123'),
            'role' => 1,
            'rfid_number' => 61300211875,
            'year_level' => 3,
            'course_id' => '15',
            'id_number' => 3231028,
            'department_id' => 4,
        ]);

        
        $this->call(OffenseSeeder::class);
        $this->call(OffensePenaltySeeder::class);

    }
}
