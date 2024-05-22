<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_id' => Ticket::_generateTicketId(),
            'type' => $this->faker->randomElement(['visitor', 'consumer']),
            'price' => $this->faker->randomElement([null, 1000]),
            'user_id' => User::inRandomOrder()->where('role', 'caissier')->first()->id,
            'created_at' => now(),
            'updated_at' => now(),
            'total' => 0,
            'number' => 0
        ];
    }

    public function visitorTickets()
    {
        usleep(1000);
        return $this->state(fn (array $attributes) => [
            'type' => 'visitor',
            'price' => 1000
        ]);
    }
    public function consumerTickets()
    {
        usleep(1000);
        return $this->state(fn (array $attributes) => [
            'type' => 'consumer',
            'price' => null
        ]);
    }
}
