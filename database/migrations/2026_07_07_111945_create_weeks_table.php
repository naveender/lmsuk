<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('weeks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->string('name');
            $table->date('due_date')->nullable();
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });

        Schema::table('course_paper', function (Blueprint $table) {
            $table->unsignedBigInteger('week_id')->nullable()->after('paper_id');
            $table->integer('week')->nullable()->change();

            $table->foreign('week_id')->references('id')->on('weeks')->onDelete('set null');
        });

        // Migrate existing data
        $existingRelations = DB::table('course_paper')->get();
        foreach ($existingRelations as $relation) {
            if ($relation->week) {
                // Find or create week
                $weekId = DB::table('weeks')->insertGetId([
                    'course_id' => $relation->course_id,
                    'name' => 'Week ' . $relation->week,
                    'due_date' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('course_paper')
                    ->where('id', $relation->id)
                    ->update(['week_id' => $weekId]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_paper', function (Blueprint $table) {
            $table->dropForeign(['week_id']);
            $table->dropColumn('week_id');
            $table->integer('week')->nullable(false)->change();
        });

        Schema::dropIfExists('weeks');
    }
};
