<?php

namespace Database\Seeders;

use App\Models\Offense;
use \App\Models\OffensePenalty;
use Illuminate\Database\Seeder;


class OffensePenaltySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find 'Smoking on Campus'
        $assault = Offense::where('name', 'Physical Assault')->first();
        $studentId = Offense::where('name', 'ID Policy Non-compliance')->first();

        if ($assault) {
            OffensePenalty::create([
                'offense_id' => $assault->id,
                'level' => 1,
                'penalty_description' => 'Suspension for 5 days.'
            ]);

            OffensePenalty::create([
                'offense_id' => $assault->id,
                'level' => 2,
                'penalty_description' => 'Suspension for 1 semester.'
            ]);

            OffensePenalty::create([
                'offense_id' => $assault->id,
                'level' => 3,
                'penalty_description' => 'Expulsion'
            ]);
        }

        if ($studentId) {
            OffensePenalty::create([
                'offense_id' => $studentId->id,
                'level' => 1,
                'penalty_description' => 'Campus entry authorized. Requires signed undertaking at Dean’s Office'
            ]);

            OffensePenalty::create([
                'offense_id' => $studentId->id,
                'level' => 2,
                'penalty_description' => 'Campus entry authorized. Requires signed undertaking at Dean’s Office'
            ]);

            OffensePenalty::create([
                'offense_id' => $studentId->id,
                'level' => 3,
                'penalty_description' => '5 hours of University Service to be completed within 1 week. Consecutive offenses incur an additional 5 hours per violation.'
            ]);
        }
    }
}
