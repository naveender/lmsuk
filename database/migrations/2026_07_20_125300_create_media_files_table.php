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
        Schema::create('media_files', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type'); // youtube, vimeo, video_file, video_url, s3, wasabi, google_drive, iframe
            $table->text('path')->nullable(); // URL, file path, ID, or iframe code depending on type
            $table->string('storage_disk')->nullable(); // local, s3, wasabi
            $table->string('original_name')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('status')->default('completed'); // pending, uploading, completed, failed
            $table->string('upload_id')->nullable(); // tracking chunks
            $table->json('metadata')->nullable(); // additional metadata
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_files');
    }
};
