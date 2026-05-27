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
        Schema::table('papers', function (Blueprint $table) {
            $table->unsignedBigInteger('topic_id')->nullable()->after('subject_id');
            $table->unsignedBigInteger('subtopic_id')->nullable()->after('topic_id');

            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('set null');
            $table->foreign('subtopic_id')->references('id')->on('topics')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('papers', function (Blueprint $table) {
            $table->dropForeign(['topic_id']);
            $table->dropForeign(['subtopic_id']);
            $table->dropColumn(['topic_id', 'subtopic_id']);
        });
    }
};
