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
        Schema::create('homeworks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('topic_id')->nullable()->constrained('topics')->onDelete('set null');
            $table->foreignId('subtopic_id')->nullable()->constrained('topics')->onDelete('set null');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('year_group_id')->constrained('year_groups')->onDelete('cascade');
            $table->string('academic_year');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // File upload attributes
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_type', 50)->nullable();
            $table->string('file_size', 50)->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('course_homework', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('homework_id')->constrained('homeworks')->onDelete('cascade');
            $table->integer('week')->default(1);
            $table->foreignId('week_id')->nullable()->constrained('weeks')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_homework');
        Schema::dropIfExists('homeworks');
    }
};
