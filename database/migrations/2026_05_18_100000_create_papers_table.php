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
        Schema::create('papers', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('test'); // 'test' or 'exam'
            $table->string('title');
            $table->text('instruction')->nullable();
            
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // Tutor or Admin who created the paper
            
            $table->string('academic_year')->nullable();
            $table->string('difficulty')->nullable();
            $table->integer('total_time')->default(0); // in minutes
            $table->integer('default_marks')->default(1); // default marks per question
            $table->boolean('question_pooling')->default(false); // Enable/Disable pooling

            // Advanced Configurations
            $table->boolean('allow_attempt_without_signup')->default(false);
            $table->boolean('allow_reattempt_question')->default(false);
            $table->boolean('display_result_question_by_question')->default(false);
            $table->boolean('allow_instant_feedback')->default(false);
            $table->boolean('hide_result')->default(false);
            $table->boolean('shuffle_questions')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('set null');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('paper_question', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('paper_id');
            $table->unsignedBigInteger('question_id');
            $table->integer('sort_order')->default(0);
            $table->integer('marks')->nullable(); // Custom marks for this question in this paper, if overridden
            $table->timestamps();

            $table->foreign('paper_id')->references('id')->on('papers')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paper_question');
        Schema::dropIfExists('papers');
    }
};
