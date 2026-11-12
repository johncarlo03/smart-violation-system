<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('offense_penalties', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->unsignedBigInteger('offense_id');
            $table->string('name');
            $table->integer('level');
            $table->text('penalty_description');
            $table->timestamps();

            // Explicit foreign key constraint definition
            $table->foreign('offense_id')
                  ->references('id')
                  ->on('offenses')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offense_penalties');
    }
};