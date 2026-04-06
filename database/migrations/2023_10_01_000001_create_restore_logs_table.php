<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestoreLogsTable extends Migration
{
    public function up()
    {
        Schema::create('restore_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type'); //Manual, Automatic, System, Server
            $table->string('file_name');
            $table->string('action');
            $table->string('message');
            $table->json('steps');
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('restore_logs');
    }
}
