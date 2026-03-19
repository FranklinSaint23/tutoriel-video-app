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

        $videos = Video::with('category')
            ->where('is_published', true)
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%")
                            ->orWhereHas('category', function ($q) use ($search) {
                                $q->where('name', 'like', "%{$search}%");
                            });
            })
            ->latest()
            ->paginate(6);

        /** @var \Illuminate\Pagination\LengthAwarePaginator $videos */
        $videos->withQueryString(); 

        return view('videos.index', compact('videos'));
    }

    public function show(string $slug): View
    {
        // On récupère la vidéo via son slug avec sa catégorie
        $video = Video::where('slug', $slug)->where('is_published', true)->firstOrFail();
        
        // On récupère quelques suggestions (vidéos de la même catégorie)
        $suggestions = Video::where('category_id', $video->category_id)
            ->where('id', '!=', $video->id)
            ->limit(5)
            ->get();

        return view('videos.show', compact('video', 'suggestions'));
    }
}