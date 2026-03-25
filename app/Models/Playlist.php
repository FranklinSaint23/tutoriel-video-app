<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name'];

    /**
     * L'utilisateur qui possède la playlist.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Les vidéos contenues dans la playlist.
     * On utilise withPivot pour récupérer la 'position' dans la table de liaison.
     */
    public function videos()
    {
        return $this->belongsToMany(Video::class, 'playlist_video')
                    ->withPivot('position')
                    ->orderBy('playlist_video.position');
    }
}