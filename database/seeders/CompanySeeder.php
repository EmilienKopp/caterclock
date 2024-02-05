<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Project;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::factory()->create([
            'name' => 'YakToku',
        ]);

        $feliseed= Company::factory()->create([
            'name' => 'Feliseed',
        ]);

        $SINC = Company::factory()->create([
            'name' => 'SINC',
        ]);

        Project::factory()->create([
            'name' => 'Seishinsha',
            'company_id' => $feliseed->id,
        ]);

        Project::factory()->create([
            'name' => 'NAiN',
            'company_id' => $feliseed->id,
        ]);

         Project::factory()->create([
            'name' => 'SINC',
            'company_id' => $SINC->id,
        ]);

        Company::factory()
            ->count(5)
            ->has(Project::factory()->count(2))
            ->create();
    }
}
