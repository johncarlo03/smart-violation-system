<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OffenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offenses = [
            'Academic' => ['Plagiarism', 'False Authorship/Contract Cheating', 'Collusion', 'Falsifying Data/Evidence', 'Exam Proxy', 'Grade Tampering', 'Exam Collusion', 'Test Leaking', 'Program Non-Attendance'],
            'Non-Academic' => ['ID Policy Non-compliance', 'Improper Uniform/Haircut', 'Unauthorized Gadget Use', 'Class/Activity Disturbance', 'Damage/Misuse of School Property', 'Unauthorized After-Hours Stay', 'Speeding/Excessive Vehicle Noise', 'Unruly Behavior on Campus', 'Use of Vulgar/Profane Language', 'Possession of Gambling Tools', 'Disrespect towards Personnel/Visitors', 'Disobedience to Lawful Orders'],
            'Serious' => ['Smoking on Campus', 'Vandalism', 'Gambling', 'Bullying'],
            'Very Serious' => ['Possession of Illegal Drugs', 'Theft', 'Physical Assault', 'Carrying Deadly Weapons']
        ];

        foreach ($offenses as $category => $names) {
            foreach ($names as $name) {
                \App\Models\Offense::create([
                    'name' => $name,
                    'category' => $category
                ]);
            }
        }
    }
}
