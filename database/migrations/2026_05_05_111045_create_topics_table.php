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
        Schema::create('topics', function (Blueprint $table) {
            $table->id();

            $table->string('code')->nullable();
            $table->string('name');

            // self reference (topic -> subtopic)
            $table->unsignedBigInteger('parent')->nullable();

            $table->string('slug')->unique();

            $table->string('thumbnail')->nullable();
            $table->string('sub_category_thumbnail')->nullable();

            $table->timestamps();
            
            // Foreign key for hierarchy
            $table->foreign('parent')
                  ->references('id')
                  ->on('topics')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
