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

        $freelanceRole = \App\Models\Role::where('name', 'freelancer')->first();

        $feliseed = Company::where('name', 'Feliseed')->first();
        $emilien->companies()->attach($feliseed, ['role_id' => $freelanceRole->id]);

        $SINC = Company::where('name', 'SINC')->first();
        $emilien->companies()->attach($SINC, ['role_id' => $freelanceRole->id]);

        $emilien->roles()->attach($freelanceRole->id);

        User::factory()->create([
            'name' => 'Kanzaki Rena',
            'email' => 'kanzaki@example.com',
            'password' => bcrypt('password'),
        ]);

        $ownerRole = \App\Models\Role::where('name', 'owner')->first();
        $metaPlanning = Company::where('name', 'Meta Planning')->first();
        $nishimura = User::factory()->create([
            'name' => 'Nishimura San',
            'email' => 'nishimura@example.com',
            'password' => bcrypt('password'),
        ]);
        $nishimura->companies()->attach($metaPlanning, ['role_id' => $ownerRole->id]);
        $nishimura->roles()->attach($ownerRole->id);

        User::factory()
            ->count(10)
            ->create();
    }
}
