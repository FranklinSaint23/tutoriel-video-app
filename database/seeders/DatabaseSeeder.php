<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Video;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Création du Super Utilisateur
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('admin1234'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Création de la catégorie obligatoire pour PostgreSQL
        // On utilise DB::table pour être sûr que ça passe même sans modèle Category
        $categoryId = DB::table('categories')->where('slug', 'general')->value('id');
        
        if (!$categoryId) {
            $categoryId = DB::table('categories')->insertGetId([
                'name' => 'Général',
                'slug' => 'general',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Liste des 10 vidéos avec les colonnes exactes de ta base (slug, video_url, category_id)
        $videos = [
            ['title' => 'Big Buck Bunny', 'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4', 'description' => 'Animation classique de lapin.'],
            ['title' => 'Elephant Dream', 'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4', 'description' => 'Premier projet de film ouvert avec Blender.'],
            ['title' => 'For Bigger Blazes', 'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4', 'description' => 'Vidéo de démonstration technologique.'],
            ['title' => 'For Bigger Escapes', 'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4', 'description' => 'Extrait vidéo publicitaire chromecast.'],
            ['title' => 'For Bigger Fun', 'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4', 'description' => 'Animation amusante et colorée.'],
            ['title' => 'For Bigger Joyrides', 'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4', 'description' => 'Test de lecture vidéo rapide.'],
            ['title' => 'For Bigger Meltdowns', 'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerMeltdowns.mp4', 'description' => 'Démonstration de flux vidéo fluide.'],
            ['title' => 'Subaru Outbacks', 'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/SubaruOutbackOnStreetAndDirt.mp4', 'description' => 'Voiture sur route et terre.'],
            ['title' => 'Tears of Steel', 'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/TearsOfSteel.mp4', 'description' => 'Film de science-fiction open-source.'],
            ['title' => 'We Are Going On Bullrun', 'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/WeAreGoingOnBullrun.mp4', 'description' => 'Course automobile de test.'],
        ];

        foreach ($videos as $video) {
            Video::updateOrCreate(
                ['title' => $video['title']],
                [
                    'slug' => Str::slug($video['title']),
                    'description' => $video['description'],
                    'video_url' => $video['url'], // Ta colonne obligatoire video_url
                    'category_id' => $categoryId,  // Ta colonne obligatoire category_id
                    'level' => 'Débutant',
                ]
            );
        }
    }
}