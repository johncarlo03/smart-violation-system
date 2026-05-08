<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OffensePenaltySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find 'Smoking on Campus'
        $assault = \App\Models\Offense::where('name', 'Physical Assault')->first();

        if ($assault) {
            \App\Models\OffensePenalty::create([
                'offense_id' => $assault->id,
                'level' => 1,
                'penalty_description' => 'Suspension for 5 days.'
            ]);

            \App\Models\OffensePenalty::create([
                'offense_id' => $assault->id,
                'level' => 2,
                'penalty_description' => 'Suspension for 1 semester.'
            ]);

            \App\Models\OffensePenalty::create([
                'offense_id' => $assault->id,
                'level' => 3,
                'penalty_description' => 'Expulsion'
            ]);
        }
    }
}
