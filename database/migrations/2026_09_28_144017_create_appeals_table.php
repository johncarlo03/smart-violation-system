<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appeals', function (Blueprint $table) {
    $table->id();

    $table->foreignId('violation_id')
        ->constrained('violations')
        ->cascadeOnDelete();

    $table->foreignId('student_id')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->text('reason');

    $table->string('evidence_image')->nullable();

    $table->enum('status', [
        'pending',
        'approved',
        'denied'
    ])->default('pending');

    $table->text('sao_response')->nullable();

    $table->foreignId('reviewed_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->timestamp('reviewed_at')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appeals');
    }
};
