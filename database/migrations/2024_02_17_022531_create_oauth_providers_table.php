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
        Schema::dropIfExists('oauth_providers');
        Schema::create('oauth_providers', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('redirect_uri');
            $table->text('token_uri')->nullable();
            $table->text('profile_uri')->nullable();
            $table->timestamps();
        });

        Schema::create('identities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('oauth_provider_id')->constrained();
            $table->text('oauth_id');
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->text('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oauth_providers');
        Schema::dropIfExists('identities');
    }
};
