<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Course::insert([
            ['name' => 'BSEE', 'department_id' => 1],
            ['name' => 'BSME', 'department_id' => 1],
            ['name' => 'BSCE', 'department_id' => 1],
            ['name' => 'BSIE', 'department_id' => 1],
            ['name' => 'BSCpE', 'department_id' => 1],

            ['name' => 'BEED', 'department_id' => 2],
            ['name' => 'BTLEd', 'department_id' => 2],
            ['name' => 'BSEd-MATH', 'department_id' => 2],
            ['name' => 'BSEd-SCI', 'department_id' => 2],
            ['name' => 'BSEd-ENG', 'department_id' => 2],
            ['name' => 'BSEd-SOCSCI', 'department_id' => 2],


            ['name' => 'BSTM', 'department_id' => 3],
            ['name' => 'BSHM', 'department_id' => 3],
            ['name' => 'BSBA', 'department_id' => 3],

            ['name' => 'BSIT', 'department_id' => 4],
            ['name' => 'BSMX', 'department_id' => 4],
            ['name' => 'BIT-COMP', 'department_id' => 4],
            ['name' => 'BIT-DRAFT', 'department_id' => 4],
            ['name' => 'BIT-ELEC', 'department_id' => 4],
            ['name' => 'BIT-ELEX', 'department_id' => 4],
        ]);

    }
}
