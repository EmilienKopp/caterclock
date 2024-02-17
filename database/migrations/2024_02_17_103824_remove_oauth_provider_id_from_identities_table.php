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
        Schema::table('identities', function (Blueprint $table) {
            $table->dropForeign(['oauth_provider_id']);
            $table->dropColumn('oauth_provider_id');
        });

        Schema::dropIfExists('oauth_providers');
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('oauth_providers', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('redirect_uri');
            $table->text('token_uri')->nullable();
            $table->text('profile_uri')->nullable();
            $table->timestamps();
        });

        Schema::table('identities', function (Blueprint $table) {
            $table->foreignId('oauth_provider_id')->constrained();
        });
    }
};
