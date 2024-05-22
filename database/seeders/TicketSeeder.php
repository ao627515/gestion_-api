<?php

namespace Database\Seeders;

use App\Models\Hall;
use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            // Générer une date aléatoire dans le passé pour created_at
            $createdAt = now()->addHour(random_int(-24, -1));

            // Compter le nombre de tickets de type 'visitor' créés à cette date
            $visitorCount = Ticket::ticketsCount('visitor', $createdAt->toDateString());

            // Créer le ticket avec le numéro incrémenté
            Ticket::factory()
                ->visitorTickets()
                ->create([
                    'created_at' => $createdAt,
                    'total' => fn ($attributes) => $attributes['price'],
                    'number' => $visitorCount + 1
                ]);
        }


        for ($i = 0; $i < 10; $i++) {
            Ticket::factory()
                ->consumerTickets()
                ->afterCreating(function (Ticket $ticket) {
                    $numberOfHalls = Hall::count();
                    $numberOfTickets = rand(1, $numberOfHalls);
                    $halls = Hall::inRandomOrder()->take($numberOfTickets)->get();
                    foreach ($halls as $hall) {
                        $ticket->halls()->attach($hall->id);
                    }
                })
                ->create(
                    [
                        'created_at' => now()->addHour(random_int(-24, -1)),
                    ]
                );
        }
    }
}
