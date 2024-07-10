<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserRole;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Créer des rôles spécifiques
        // UserRole::factory()->admin()->create();
        // UserRole::factory()->cashier()->create();
        // UserRole::factory()->chief_accountant()->create();

        // Créer des rôles supplémentaires aléatoires
        // UserRole::factory(10)->create();
    }
}
