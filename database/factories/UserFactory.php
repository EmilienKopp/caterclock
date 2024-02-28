<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function employee(){
        return $this->afterCreating(function ($user) {
            $user->roles()->attach(Role::of('employee'));
        });
    }

    public function manager(){
        return $this->afterCreating(function ($user) {
            $user->roles()->attach(Role::of('manager'));
        });
    }

    public function admin(){
        return $this->afterCreating(function ($user) {
            $user->roles()->attach(Role::of('admin'));
        });
    }

    public function freelancer() {
        return $this->afterCreating(function ($user) {
            $user->roles()->attach(Role::of('freelancer'));
        });
    }

    public function owner() {
        return $this->afterCreating(function ($user) {
            $user->roles()->attach(Role::of('owner'));
        });
    }


    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
