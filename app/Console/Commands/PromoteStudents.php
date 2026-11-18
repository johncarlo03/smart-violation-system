<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class PromoteStudents extends Command
{
    protected $signature = 'students:promote-year-level';

    protected $description = 'Automatically promote students to the next year level';

    public function handle()
    {
        $promoted = User::where('role', 1)
            ->whereNotNull('year_level')
            ->where('year_level', '<', 4)
            ->increment('year_level');

        $this->info("Successfully promoted {$promoted} students.");

        return Command::SUCCESS;
    }
}