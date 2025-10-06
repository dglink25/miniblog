<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ✅ Création de l’administrateur principal
        User::updateOrCreate(
            ['email' => 'dondiegue21@gmail.com'], // critère de recherche
            [
                'name' => 'Diègue HOUNDOKINNOU',
                'password' => Hash::make('12345678'),
                'is_admin' => true, // ou 1 selon ton type de colonne
            ]
        );

        // Si tu veux aussi garder des utilisateurs de test :
        // User::factory(10)->create();
    }
}
