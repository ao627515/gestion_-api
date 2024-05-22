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
        Hall::factory()->create([
            'name' => "Piscine Adulte",
            'price' => 3000
        ]);

        Hall::factory()->create([
            'name' => "Piscine A Vague ",
            'price' => 3000
        ]);

        Hall::factory()->create([
            'name' => "Piscine Enfant",
            'price' => 3000
        ]);

        Hall::factory()->create([
            'name' => "Piscine G-Toboguant",
            'price' => 3000
        ]);
    }
}
