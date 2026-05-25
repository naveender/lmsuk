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
            $table->unsignedBigInteger('year_group_id')->nullable()->after('class_id');
            $table->foreign('year_group_id')->references('id')->on('year_groups')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('papers', function (Blueprint $table) {
            $table->dropForeign(['year_group_id']);
            $table->dropColumn('year_group_id');
        });
    }
};
