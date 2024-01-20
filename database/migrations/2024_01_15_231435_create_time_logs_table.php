<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTimeLogsTable extends Migration
{
    public function up()
    {
        Schema::create('time_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->date('date')->default(DB::raw('DATE(CURRENT_TIMESTAMP)'));
            $table->timestampTz('in_time');
            $table->timestampTz('out_time')->nullable();
            $table->timestampTz('break_start')->nullable();
            $table->timestampTz('break_end')->nullable();
            $table->integer('break_duration')->nullable();
            $table->integer('total_duration')->nullable();
            $table->boolean('is_running')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('time_logs');
    }
}
