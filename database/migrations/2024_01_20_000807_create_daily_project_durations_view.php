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
        DB::statement('DROP VIEW IF EXISTS daily_project_durations');
        // PGSQL
        DB::statement('CREATE OR REPLACE VIEW daily_project_durations AS
            SELECT
                tl.user_id,
                u.name as user_name,
                tl.project_id,
                p.name as project_name,
                date,
                SUM(EXTRACT(EPOCH FROM (out_time - in_time)) ) as total_seconds,
                SUM(EXTRACT(EPOCH FROM (out_time - in_time)) / 60 ) as total_minutes
            FROM
                time_logs AS tl
            JOIN users AS u ON u.id = user_id
            JOIN projects AS p ON p.id = project_id
            GROUP BY
                tl.user_id, tl.project_id, user_name, project_name, date;
        ');

        // MYSQL
        // DB::statement('CREATE VIEW daily_project_durations AS
        //     SELECT
        //         tl.user_id,
        //         u.name as user_name,
        //         tl.project_id,
        //         p.name as project_name,
        //         tl.date,
        //         SUM(TIMESTAMPDIFF(SECOND, tl.in_time, COALESCE(tl.out_time, NOW()))) as total_seconds,
        //         SUM(TIMESTAMPDIFF(MINUTE, tl.in_time, COALESCE(tl.out_time, NOW()))) as total_minutes
        //     FROM
        //         time_logs AS tl
        //     JOIN users AS u ON u.id = tl.user_id
        //     JOIN projects AS p ON p.id = tl.project_id
        //     GROUP BY
        //         tl.user_id, tl.project_id, tl.date;
        // ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS daily_project_durations');
    }
};
