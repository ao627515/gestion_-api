<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserRole>
 */
class UserRoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'libelle' => $this->faker->word,
            'created_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * State for admin role.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function admin()
    {
        return $this->state(fn (array $attributes) => [
            'libelle' => 'administrator',
        ]);
    }

    /**
     * State for cashier role.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function cashier()
    {
        return $this->state(fn (array $attributes) => [
            'libelle' => 'cashier',
        ]);
    }

    /**
     * State for chief accountant role.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function chief_accountant()
    {
        return $this->state(fn (array $attributes) => [
            'libelle' => 'chief_accountant',
        ]);
    }
}
