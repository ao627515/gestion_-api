<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AccessRight>
 */
class AccessRightFactory extends Factory
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
            'description' => $this->faker->sentence,
            'user_id' => User::inRandomOrder()->whereHas('role', function($query) {
                $query->where('libelle', 'administrator');
            })->first()->id
        ];
    }
}
