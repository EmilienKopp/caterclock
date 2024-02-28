<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ConnectionRequest;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Lottery;
use App\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConnectionRequest>
 */
class ConnectionRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sender_id' => User::factory(),
            'company_id' => null,
            'receiver_id' => null,
            'status' => 'pending',
            'role_id' => $this->faker->randomElement([
                Role::id('employee'),
                Role::id('manager'),
                Role::id('freelancer'),
            ]),
        ];
    }

    public function employeeToCompany(){
        return $this->state(function (array $attributes) {
            $company = Company::factory()->create();
            return [
                'company_id' => $company->id,
                'receiver_id' => null,
                'sender_id' => User::factory()->employee(),
                'role_id' => Role::id('employee'),
            ];
        });
    }

    public function managerToCompany(){
        return $this->state(function (array $attributes) {
            $company = Company::factory()->create();
            return [
                'company_id' => $company->id,
                'receiver_id' => null,
                'sender_id' => User::factory()->manager(),
                'role_id' => Role::id('manager'),
            ];
        });
    }

    public function freelancerToCompany(){
        return $this->state(function (array $attributes) {
            $company = Company::factory()->create();
            return [
                'company_id' => $company->id,
                'receiver_id' => null,
                'sender_id' => User::factory()->freelancer(),
                'role_id' => Role::id('freelancer'),
            ];
        });
    }

    public function userToUser(){
        return $this->state(function (array $attributes) {
            $sender = $this->faker->randomElement([
                User::factory()->employee()->create(),
                User::factory()->manager()->create(),
                User::factory()->freelancer()->create(),
                User::factory()->owner()->create(),
            ]);
            $receiver = $this->faker->randomElement([
                User::factory()->employee()->create(),
                User::factory()->manager()->create(),
                User::factory()->freelancer()->create(),
                User::factory()->owner()->create(),
            ]);
            return [
                'company_id' => null,
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'role_id' => $sender->roles->first()->id,
            ];
        });
    }
    


}
