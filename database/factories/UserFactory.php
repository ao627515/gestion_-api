<?php

namespace Database\Factories;

use App\Models\UserRole;
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
    protected static ?string $password = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lastname' => $this->faker->lastName,
            'firstname' => $this->faker->firstName,
            'registration_number' => uniqid(),
            'phone' => $this->faker->numerify('########'),
            'role_id' => UserRole::inRandomOrder()->first()->id ?? 1,
            'password' => static::$password ??= Hash::make('password'),
            'birthday' => $this->faker->dateTimeBetween('-30 years', '-18 years'),
            'ref_cinb' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * State for admin user.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function admin()
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => UserRole::factory()->admin()->create()->id,
        ]);
    }

    /**
     * State for cashier user.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function cashier()
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => UserRole::factory()->cashier()->create()->id,
        ]);
    }

    /**
     * State for chief accountant user.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function chief_accountant()
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => UserRole::factory()->chief_accountant()->create()->id,
        ]);
    }
}
