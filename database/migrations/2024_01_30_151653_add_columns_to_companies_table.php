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
        Schema::table('companies', function (Blueprint $table) {
            $table->foreignId('representative_id')->references('id')->on('users')->nullable()
                    ->constrained()->onDelete('cascade');
            $table->string('contact_phone')->nullable();
            $table->integer('employees_count')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['representative_id']);
            $table->dropColumn('representative_id');
            $table->dropColumn('contact_phone');
            $table->dropColumn('employees_count');
        });
    }
};
