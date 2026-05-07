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
    Schema::create('violations', function (Blueprint $table) {
        $table->id();
        // Foreign Keys
        $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('cso_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('department_id')->constrained()->onDelete('cascade');
        
        // Violation Details
        $table->string('type'); // e.g., "Academic: Plagiarism"
        $table->text('description');
        $table->string('evidence_image')->nullable();
        
        // Status for SAO Review
        $table->enum('status', ['pending', 'resolved', 'void'])->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violations');
    }
};
