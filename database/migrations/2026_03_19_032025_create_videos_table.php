<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique(); // URL unique pour chaque vidéo
            $table->text('description');
            $table->string('video_url');      // Lien vers le stockage (ex: Cloudinary)
            $table->string('thumbnail_url')->nullable();
            
            // Enumération pour la qualité des données (Niveaux du projet)
            $table->enum('level', ['Débutant', 'Intermédiaire', 'Avancé'])->default('Débutant');
            
            // Relations (Génie Logiciel : Intégrité référentielle)
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            
            $table->boolean('is_published')->default(false); // Système de validation
            $table->softDeletes(); // Sécurité : permet de restaurer une vidéo supprimée
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
