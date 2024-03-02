<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

// all timezones using Carbon


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $timezones = collect(DateTimeZone::listIdentifiers(DateTimeZone::ALL))->toArray();

        Schema::table('time_logs', function (Blueprint $table) use($timezones) {
            $table->enum('timezone',$timezones)->nullable()->after('date');
        });

        Schema::table('users', function (Blueprint $table) use($timezones) {
            $table->enum('timezone',$timezones)->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_logs', function (Blueprint $table) {
            $table->dropColumn('timezone');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('timezone');
        });
    }
};
