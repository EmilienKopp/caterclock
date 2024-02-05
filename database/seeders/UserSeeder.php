<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emilien = User::factory()->create([
            'name' => 'Emilien Kopp',
            'email' => 'ec2k@example.com',
            'password' => bcrypt('password'),
        ]);

        $feliseed = Company::where('name', 'Feliseed')->first();
        $emilien->companies()->attach($feliseed, ['position' => 'hired_freelance']);

        $SINC = Company::where('name', 'SINC')->first();
        $emilien->companies()->attach($SINC, ['position' => 'hired_freelance']);

        User::factory()->create([
            'name' => 'Kanzaki Rena',
            'email' => 'kanzaki@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()
            ->count(10)
            ->create();
    }
}
