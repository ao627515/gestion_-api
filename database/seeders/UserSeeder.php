<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'caissier 1',
            'phone' => '12345678',
            'role' => 'caissier',
        ]);

        User::factory()->create([
            'name' => 'Gerant 1',
            'phone' => '87654321',
            'role' => 'gerant',
        ]);
    }
}
