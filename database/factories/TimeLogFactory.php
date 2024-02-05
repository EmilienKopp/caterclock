<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
        $in = $this->faker->dateTimeThisMonth;
        return [
            'in_time' => $in,
            'out_time' => $this->faker->dateTimeAfter($in),
        ];
    }
}
