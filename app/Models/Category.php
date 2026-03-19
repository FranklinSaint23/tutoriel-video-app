<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    // Autoriser le remplissage de ces champs (Sécurité Mass Assignment)
    protected $fillable = ['name', 'slug', 'description'];

    /**
     * Récupère toutes les vidéos de cette catégorie.
     */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }
}
