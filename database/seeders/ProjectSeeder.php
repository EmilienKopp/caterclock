<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dummyUser = User::find(1);
        \App\Models\Project::factory()->create([
            'name' => '正進社',
            'user_id' => $dummyUser->id,
        ]);
        \App\Models\Project::factory()->create([
            'name' => 'NAiN',
            'user_id' => $dummyUser->id,
        ]);
    }
}
