<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\TimeLog;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimeLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emilien = User::where('name', 'Emilien Kopp')->first();
        $kanzaki = User::where('name', 'Kanzaki Rena')->first();

        $emiliensProjects = Project::whereCompany('name','Feliseed')->get();

        $emiliensProjects->each(function (Project $project) use ($emilien) {
            TimeLog::factory()
                ->count(5)
                ->for($project)
                ->for($emilien)
                ->create();
        });
    }
}
