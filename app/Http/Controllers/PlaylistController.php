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

    public function index()
    {
        // On récupère les playlists avec le compte des vidéos pour l'affichage
        $playlists = auth()->user()->playlists()
            ->withCount('videos')
            ->latest()
            ->get();

        return view('playlists.index', compact('playlists'));
    }

    public function show(Playlist $playlist, Request $request)
    {
        // Sécurité : Vérifier que l'utilisateur est le propriétaire
        if ($playlist->user_id !== auth()->id()) {
            abort(403);
        }

        // Charger les vidéos de la playlist
        $playlist->load('videos');

        // Déterminer la vidéo à lire (la première par défaut ou celle passée en paramètre)
        $currentVideo = $request->has('video') 
            ? $playlist->videos->where('id', $request->video)->first() 
            : $playlist->videos->first();

        return view('playlists.show', compact('playlist', 'currentVideo'));
    }
}
