<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $videos = Video::with('category')
            ->where('user_id', auth()->id()) // Sécurité : On ne voit que nos vidéos
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($catQuery) use ($search) {
                        $catQuery->where('name', 'like', "%{$search}%");
                    });
                });
            })
            ->latest()
            ->paginate(10);
        /** @var \Illuminate\Pagination\LengthAwarePaginator $videos */
        $videos->withQueryString(); // Garde la recherche active lors du changement de page

        return view('admin.videos.index', compact('videos', 'search'));
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

    public function edit(Video $video)
    {
        $categories = Category::all();
        return view('admin.videos.edit', compact('video', 'categories'));
    }

    public function update(Request $request, Video $video)
    {
        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        $video->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'level' => $request->level,
        ]);

        return redirect()->route('admin.videos.index')->with('success', 'Vidéo mise à jour !');
    }

    public function destroy(Video $video)
    {
        $video->delete(); // Grâce à SoftDeletes, elle n'est pas vraiment supprimée du disque dur
        return redirect()->route('admin.videos.index')->with('success', 'Vidéo supprimée.');
    }
}