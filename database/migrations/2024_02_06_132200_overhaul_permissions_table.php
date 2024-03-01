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
        Schema::dropIfExists('permissions');
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained();
            $table->enum('ability', ['create', 'index', 'update', 'delete', 'show']);
            $table->string('model',100);
            $table->unique(['role_id', 'ability', 'model']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('model',100);
            $table->string('table')->nullable();
            $table->integer('create')->default(1000);
            $table->integer('read')->default(1000);
            $table->integer('update')->default(1000);
            $table->integer('delete')->default(1000);
            $table->timestamps();
        });
    }
};
