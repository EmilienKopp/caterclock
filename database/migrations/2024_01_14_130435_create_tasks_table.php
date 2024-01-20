<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_categories', function (Blueprint $table) {
            $table->id();
            $table->text('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('task_categories')->onDelete('cascade');
            $table->text('name');
            $table->text('description')->nullable();
            $table->decimal('rate', 8, 2, true)->nullable();
            $table->time('estimated_time')->nullable();
            $table->time('actual_time')->nullable();
            $table->date('date')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->text('status');
            $table->timestamps(); // Includes created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('task_categories');
    }
}
