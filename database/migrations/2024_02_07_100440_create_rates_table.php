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
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('task_category_id')->constrained();
            $table->decimal('rate', 10, 2);
            $table->string('currency', 3);
            $table->date('valid_from');
            $table->date('valid_to')->nullable();
            $table->unique(['project_id', 'user_id', 'task_category_id', 'valid_from']);
            $table->timestamps();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->string('currency', 3)->default('JPY')->after('balance_high');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rates');

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
    }
};
