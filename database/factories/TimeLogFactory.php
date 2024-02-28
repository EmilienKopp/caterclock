<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TimeLog;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Models\Project;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TimeLog>
 */
class TimeLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $in = $this->faker->dateTimeThisMonth();
        $out = Carbon::parse($in)->addHours($this->faker->numberBetween(1, 8));
        $user = User::factory()->create();
        $project = Project::factory()->create(
            ['user_id' => $user->id]
        );
        return [
            'in_time' => $in,
            'out_time' => $out,
            'date' => $in->format('Y-m-d'),
            'user_id' => $user->id,
            'project_id' => $project->id,
        ];
    }

}
