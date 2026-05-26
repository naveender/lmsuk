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
        Schema::create('paper_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paper_id')->constrained()->onDelete('cascade');
            $table->string('assign_type'); // 'classes', 'sessions', 'group_years', 'students'
            $table->string('assign_mode'); // 'all', 'specific'
            $table->unsignedBigInteger('target_id')->nullable(); // class_id, academic_year_id, user_id
            $table->string('target_value')->nullable(); // group_year value string
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paper_assignments');
    }
};
