<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'contact_email' => $this->faker->unique()->safeEmail,
            'address' => $this->faker->address,
            'contact_phone' => $this->faker->phoneNumber,
            'corporate_number' => $this->faker->randomNumber(8),
            'is_active' => true,
            'is_public' => $this->faker->boolean,
            'code' => $this->faker->regexify('[A-Z0-9]{6}'),
            'representative_id' => User::factory(),
        ];
    }
}
