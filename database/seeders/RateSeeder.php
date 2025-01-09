<?php

namespace Database\Seeders;

use App\Models\Rate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;

class RateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $projects = Project::all();
        
        $rates = [];
        foreach ($users as $user) {
            foreach ($projects as $project) {
                $rates[] = [
                    'user_id' => $user->id,
                    'project_id' => $project->id,
                    'rate' => rand(1000, 10000),
                ];
            }
        }
    }
}
