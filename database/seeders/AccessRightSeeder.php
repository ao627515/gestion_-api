<?php

namespace Database\Seeders;

use App\Models\AccessRight;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AccessRightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['libelle' => 'Access C-Entrée', 'description' => 'Accès à la section C-Entrée'],
            ['libelle' => 'Access C-Restaurant', 'description' => 'Accès à la section C-Restaurant'],
            ['libelle' => 'Access C-Fast-Food', 'description' => 'Accès à la section C-Fast-Food'],
            ['libelle' => 'Access C-Bar', 'description' => 'Accès à la section C-Bar'],
            ['libelle' => 'Access Admin', 'description' => 'Accès à la section Admin'],
            ['libelle' => 'Access Security', 'description' => 'Accès à la section Sécurité Profil'],
        ];

        foreach ($data as $item) {
            AccessRight::factory()->create($item);
        }
    }
}
