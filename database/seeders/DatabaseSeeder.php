<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {   
        \App\Models\Video::factory(10)->create();

    
        // 1. Création d'un administrateur réel
        // On utilise 'updateOrCreate' pour éviter les erreurs si on relance le seed
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('admin'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Création de catégories de base pour tes tutoriels
        $categories = ['Développement Web', 'Cyber Sécurité', 'Systèmes & Réseaux', 'Laravel Tips'];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['name' => $cat],
                ['slug' => \Illuminate\Support\Str::slug($cat)]
            );
        }

        // 3. Bloc de sécurité pour Faker
        // On ne lance les factories QUE si on est en local
        if (app()->environment('local')) {
            // Ici tu peux remettre tes factories si tu en as besoin sur ton PC
            // User::factory(10)->create();
        }
    }
}

