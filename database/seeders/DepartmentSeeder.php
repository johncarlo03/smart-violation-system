<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['id' => 1, 'name' => 'College of Engineering'],
            ['id' => 2, 'name' => 'College of Education, Arts, and Sciences'],
            ['id' => 3, 'name' => 'College of Management and Entrepreneurship'],
            ['id' => 4, 'name' => 'College of Technology'],
        ];

        foreach ($departments as $dept) {
            \App\Models\Department::updateOrCreate(['id' => $dept['id']], $dept);
        }
    }
}
