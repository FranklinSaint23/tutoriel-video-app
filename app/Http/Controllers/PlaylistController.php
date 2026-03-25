<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\Video;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    // Créer une playlist et y ajouter la vidéo actuelle
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'video_id' => 'required|exists:videos,id'
        ]);

        $playlist = auth()->user()->playlists()->create([
            'name' => $request->name
        ]);

        // Attachement de la vidéo à la nouvelle playlist
        $playlist->videos()->attach($request->video_id, ['position' => 0]);

        return back()->with('success', "Playlist '{$playlist->name}' créée avec succès !");
    }

    // Ajouter la vidéo à une playlist existante
    public function addVideo(Playlist $playlist, Video $video)
    {
        // Vérification de sécurité : l'utilisateur possède-t-il la playlist ?
        if ($playlist->user_id !== auth()->id()) {
            abort(403);
        }

        // Éviter les doublons
        if (!$playlist->videos->contains($video->id)) {
            $position = $playlist->videos()->count();
            $playlist->videos()->attach($video->id, ['position' => $position]);
            return back()->with('success', "Vidéo ajoutée à {$playlist->name}");
        }

        return back()->with('info', "Cette vidéo est déjà dans la playlist.");
    }
}
