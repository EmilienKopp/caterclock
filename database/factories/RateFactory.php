<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Rate;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rate>
 */
class RateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $usersToExclude = [
            User::whereLike('name', 'Emilien%')->firstOrFail(),
        ];
        return [
            'project_id' => Project::all()->random()->id,
            'user_id' => User::whereNotIn('id', $usersToExclude)->get()->random()->id,
            'task_category_id' => TaskCategory::all()->random()->id,
            'rate' => $this->faker->randomFloat(2, 0, 1000),
            'currency' => 'JPY',
            'valid_from' => $this->faker->date(),
            'valid_to' => $this->faker->date(),
        ];
    }
}
