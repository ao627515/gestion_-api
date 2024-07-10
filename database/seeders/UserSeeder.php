<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Créer des utilisateurs avec des rôles spécifiques
        User::factory()->admin()->create([
            'phone' => '0000'
        ]);
        User::factory()->cashier()->create([
            'phone' => '1111'
        ]);
        User::factory()->chief_accountant()->create([
            'phone' => '2222'
        ]);

        // Créer des utilisateurs supplémentaires aléatoires
        User::factory(50)->create();
    }
}
