<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('violations', function (Blueprint $table) {
            // 1. Add the new foreign key column
            // We make it nullable initially so existing records don't crash
            $table->foreignId('offense_id')->nullable()->after('student_id')->constrained('offenses')->onDelete('cascade');

            // 2. Remove the old text-based column
            $table->dropColumn('type');
        });
    }

    public function down(): void
    {
        Schema::table('violations', function (Blueprint $table) {
            $table->string('type')->after('student_id');
            $table->dropForeign(['offense_id']);
            $table->dropColumn('offense_id');
        });
    }
};
