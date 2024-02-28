<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->date();
        $endDate = Carbon::parse($this->faker->date())->addDays(90);
        $budgetLow = $this->faker->numberBetween(1_000_000, 90_000_000);
        $budgetHigh = $budgetLow + $this->faker->numberBetween(1_000_000, 10_000_000);
        $budgetMid = ($budgetLow + $budgetHigh) / 2;
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'budget_low' => $budgetLow,
            'budget_mid' => $budgetMid,
            'budget_high' => $budgetHigh,
            'currency' => 'JPY',
            'company_id' => null,
            'user_id' => null,
        ];
    }

    public function companyOwned() {
        return $this->state(function (array $attributes) {
            return [
                'company_id' => Company::factory()->create()->id,
                'user_id' => null,
            ];
        });
    }

    public function userOwned() {
        return $this->state(function (array $attributes) {
            return [
                'company_id' => null,
                'user_id' => User::factory()->create()->id,
            ];
        });
    }
}
