<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $videos = Video::with(['category', 'user']) // On charge l'auteur aussi
            ->where('is_published', true)
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhereHas('category', function ($catQuery) use ($search) {
                          $catQuery->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->latest()
            ->paginate(12); // 12 c'est mieux pour une grille (3x4 ou 4x3)

        return view('videos.index', compact('videos'));
    }

    public function show(string $slug): View
    {
        // On récupère la vidéo avec ses relations pour éviter les requêtes inutiles dans la vue
        $video = Video::with(['category', 'user'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
        
        $suggestions = Video::where('category_id', $video->category_id)
            ->where('id', '!=', $video->id)
            ->where('is_published', true)
            ->limit(4)
            ->get();

        return view('videos.show', compact('video', 'suggestions'));
    }
}