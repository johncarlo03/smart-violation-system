<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('violations', function (Blueprint $table) {

            // penalty_id already exists.
            // We only need to create its foreign key.
            $table->foreign('penalty_id')
                ->references('id')
                ->on('offense_penalties')
                ->nullOnDelete();

            $table->text('resolution_notes')
                ->nullable()
                ->after('status');

            $table->text('dismissal_reason')
                ->nullable()
                ->after('resolution_notes');

            $table->timestamp('resolved_at')
                ->nullable()
                ->after('dismissal_reason');
        });
    }

    public function down(): void
    {
        Schema::table('violations', function (Blueprint $table) {

            $table->dropForeign(['penalty_id']);

            $table->dropColumn([
                'resolution_notes',
                'resolved_at',
            ]);
        });
    }
};