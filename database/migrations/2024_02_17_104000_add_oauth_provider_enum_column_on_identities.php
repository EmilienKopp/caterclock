<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // DB::statement("DROP TYPE IF EXISTS oauth_providers");
        // DB::statement("CREATE TYPE oauth_providers AS ENUM ('google', 'line', 'discord', 'github', 'gitlab','facebook', 'slack')");
    
        // DB::statement("ALTER TABLE identities ADD COLUMN oauth_provider oauth_providers");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('identities', function (Blueprint $table) {
        //     $table->dropColumn('oauth_provider');
        // });

        // DB::statement("DROP TYPE oauth_providers");
    }
};
