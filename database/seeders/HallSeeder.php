<?php

namespace Database\Seeders;

use App\Models\Hall;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var int[] $prices */
        $prices = [1000, 2500, 5000, 7500];


        for ($i = 0; $i < 4; $i++) {
            Hall::factory()->create([
                'name' => "Salle ".($i+1),
                'price' => $prices[$i]
            ]);
        }
    }
}
