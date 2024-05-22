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
        // Générer une date aléatoire dans le passé pour created_at
        $createdAt = now()->addHour(random_int(-24, -1));
        for ($i = 0; $i < 10; $i++) {
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
            $consumerCount = Ticket::ticketsCount('consumer', $createdAt->toDateString());
            Ticket::factory()
                ->consumerTickets()
                ->afterCreating(function (Ticket $ticket) {
                    $numberOfHalls = Hall::count();
                    $numberOfTickets = rand(1, $numberOfHalls);
                    $halls = Hall::inRandomOrder()->take($numberOfTickets)->get();

                    foreach ($halls as $hall) {
                        $ticket->halls()->attach($hall->id);
                    }

                    $total = $halls->sum('price');
                    switch ($halls->count()) {
                        case 2:
                            $total -= 1000;
                            break;
                        case 3:
                            $total -= 1500;
                            break;
                        case 4:
                            $total -= 2000;
                            break;
                    }
                    $ticket->total = $total;
                })
                ->create([
                    'created_at' => now()->addHour(random_int(-24, -1)),
                    'number' => $consumerCount + 1,
                ]);
        }

    }


}
