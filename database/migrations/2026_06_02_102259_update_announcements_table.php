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
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->string('status')->default('active'); // active, expired, deleted
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->onDelete('set null');
            $table->boolean('is_draft')->default(true);
            $table->dateTime('show_from')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->string('priority')->default('medium'); // high, medium, low
        });

        Schema::create('announcement_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('announcement_id')->constrained('announcements')->onDelete('cascade');
            $table->string('target_type'); // all_active_students, all_tutors, class, user, year_group
            $table->unsignedBigInteger('target_id')->nullable(); // holds class_id, user_id, or year_group_id
            $table->timestamps();
        });

        Schema::create('announcement_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('announcement_id')->constrained('announcements')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('viewed_at');
        });

        Schema::create('announcement_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('announcement_id')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action'); // created, updated, published, deleted
            $table->text('details')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcement_audit_logs');
        Schema::dropIfExists('announcement_views');
        Schema::dropIfExists('announcement_targets');

        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn(['status', 'academic_year_id', 'is_draft', 'show_from', 'expires_at', 'priority']);
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->boolean('status')->default(1);
        });
    }
};
