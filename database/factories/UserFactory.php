<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            'name' => null,
            'lastname' => null,
            'firstname' => null,
            'phone' => strval(fake()->randomNumber(8)),
            'role' => fake()->randomElement(['caissier', 'gerant', 'admin']),
            'password' => static::$password ??= Hash::make('password'),
            'created_at' => now(),
            'updated_at'=> now()
        ];
    }


    public function fillName(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => fake()->name,
        ]);
    }

    public function fillLastFirstName(){
        return $this->state(fn (array $attributes) => [
            'lastname' => fake()->lastName,
            'firstname' => fake()->firstName,
        ]);
    }
}
