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
    ]);

    // Create a Campus Safety Officer (Role 2)
    \App\Models\User::create([
        'name' => 'CSO Officer',
        'email' => 'cso@ctu.edu.ph',
        'password' => bcrypt('password123'),
        'role' => 2,
    ]);

    \App\Models\User::factory(100)->create();

    // Create a Sample Student (Role 1)
    \App\Models\User::create([
        'name' => 'John Carlo C. Arias',
        'email' => 'student@ctu.edu.ph',
        'password' => bcrypt('password123'),
        'role' => 1,
    ]);
    
    }
}
