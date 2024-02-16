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
        Schema::table('projects', function (Blueprint $table) {
            $table->decimal('budget_low', 10, 2)->nullable();
            $table->decimal('budget_mid', 10, 2)->nullable();
            $table->decimal('budget_high', 10, 2)->nullable();
            $table->decimal('spent', 10, 2)->nullable();
            $table->decimal('balance_low', 10, 2)->nullable();
            $table->decimal('balance_mid', 10, 2)->nullable();
            $table->decimal('balance_high', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('budget_low');
            $table->dropColumn('budget_mid');
            $table->dropColumn('budget_high');
            $table->dropColumn('spent');
            $table->dropColumn('balance_low');
            $table->dropColumn('balance_mid');
            $table->dropColumn('balance_high');
        });
    }
};
