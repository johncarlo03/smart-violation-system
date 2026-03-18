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
        Schema::table('users', function (Blueprint $table) {
            // 1. Add the new unsigned integer column for the ID
            // Constrained() automatically links it to the 'id' on the 'departments' table
            $table->foreignId('department_id')
                ->nullable()
                ->after('course')
                ->constrained()
                ->onDelete('set null');

            // 2. Drop the old string column
            $table->dropColumn('department');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('department')->nullable()->after('course');
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
    }
};
