<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
          DB::statement("
            ALTER TABLE violations
            MODIFY status ENUM(
                'pending',
                'pending_chairman',
                'pending_dean',
                'pending_sao',
                'resolved',
                'dismissed',
                'void'
            ) NOT NULL DEFAULT 'pending'
        ");

        // Move existing pending violations to the Chairman.
        DB::table('violations')
            ->where('status', 'pending')
            ->update(['status' => 'pending_chairman']);

        // Remove the old pending value.
        DB::statement("
            ALTER TABLE violations
            MODIFY status ENUM(
                'pending_chairman',
                'pending_dean',
                'pending_sao',
                'resolved',
                'dismissed',
                'void'
            ) NOT NULL DEFAULT 'pending_chairman'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('violations')
            ->whereIn('status', [
                'pending_chairman',
                'pending_dean',
                'pending_sao',
            ])
            ->update(['status' => 'pending']);

        DB::statement("
            ALTER TABLE violations
            MODIFY status ENUM(
                'pending',
                'resolved',
                'dismissed',
                'void'
            ) NOT NULL DEFAULT 'pending'
        ");
    }
};
