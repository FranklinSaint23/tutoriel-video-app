<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence();
        return [
            'title' => $title,
            'slug' => str()->slug($title),
            'description' => $this->faker->paragraph(),
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // URL de test
            'thumbnail_url' => 'https://picsum.photos/640/480', // Image aléatoire
            'level' => $this->faker->randomElement(['Débutant', 'Intermédiaire', 'Avancé']),
            'category_id' => \App\Models\Category::factory(), // Crée une catégorie auto
            'user_id' => \App\Models\User::factory(), // Crée un utilisateur auto
            'is_published' => true,
        ];
    }
}
