<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', [
                'single_choice_radio',
                'single_choice_dropdown',
                'multiple_choice',
                'picture_choice',
                'fill_in_the_blanks',
                'matching_drag_drop',
                'matching_text',
                'free_text',
                'file_upload',
            ]);
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->unsignedBigInteger('topic_id')->nullable();
            $table->unsignedBigInteger('subtopic_id')->nullable();
            $table->string('difficulty')->nullable();
            $table->integer('marks')->default(1);
            $table->text('explanation')->nullable();
            $table->json('metadata')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('set null');
            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('set null');
            $table->foreign('subtopic_id')->references('id')->on('topics')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
