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
        // Add metadata fields to media_files table
        Schema::table('media_files', function (Blueprint $table) {
            $table->unsignedBigInteger('subject_id')->nullable()->after('description');
            $table->unsignedBigInteger('class_id')->nullable()->after('subject_id');
            $table->unsignedBigInteger('year_group_id')->nullable()->after('class_id');
            $table->string('academic_year')->nullable()->after('year_group_id');
            $table->string('duration')->nullable()->after('file_size');
            $table->string('thumbnail_path')->nullable()->after('duration');
            $table->string('publication_status')->default('published')->after('status'); // draft, published

            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('set null');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('set null');
            $table->foreign('year_group_id')->references('id')->on('year_groups')->onDelete('set null');
        });

        // Create course_media_file pivot table
        Schema::create('course_media_file', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('media_file_id')->constrained('media_files')->onDelete('cascade');
            $table->integer('week')->default(1);
            $table->foreignId('week_id')->nullable()->constrained('weeks')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_media_file');

        Schema::table('media_files', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropForeign(['class_id']);
            $table->dropForeign(['year_group_id']);

            $table->dropColumn([
                'subject_id',
                'class_id',
                'year_group_id',
                'academic_year',
                'duration',
                'thumbnail_path',
                'publication_status',
            ]);
        });
    }
};
