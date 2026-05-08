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
        Schema::create('offense_penalties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offense_id')->constrained()->onDelete('cascade');
            $table->integer('level'); // 1, 2, 3, etc.
            $table->text('penalty_description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offense_penalties');
    }
};
