<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('admin.videos.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // 1. Validation stricte (Génie Logiciel : ne jamais faire confiance à l'utilisateur)
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'video' => 'required|mimes:mp4,mov,ogg,qt|max:20000', // Max 20MB pour le test
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // 2. Gestion de l'upload des fichiers
        $videoPath = $request->file('video')->store('tutos', 'public');
        $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');

        // 3. Création en base de données
        Video::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . rand(100, 999),
            'description' => $request->description,
            'video_url' => asset('storage/' . $videoPath),
            'thumbnail_url' => asset('storage/' . $thumbnailPath),
            'category_id' => $request->category_id,
            'user_id' => auth()->id(), // <--- RÉCUPÈRE L'ID DE L'UTILISATEUR CONNECTÉ
            'level' => $request->level,
            'is_published' => true
        ]);

        return redirect()->route('videos.index')->with('success', 'Vidéo publiée avec succès !');
    }

    public function index(Request $request)
    {
        $search = $request->query('search');

        $videos = Video::with(['category', 'user']) // On ajoute 'user' ici
        // Supprime ou commente la ligne ->where('user_id', auth()->id()) 
        // pour que l'admin voie TOUT le système.
        ->when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($catQuery) use ($search) {
                      $catQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        })
            ->latest()
            ->paginate(10);
        /** @var \Illuminate\Pagination\LengthAwarePaginator $videos */
        $videos->withQueryString(); // Garde la recherche active lors du changement de page

        return view('admin.videos.index', compact('videos', 'search'));
    }


    public function edit(Video $video)
    {
        // Vérification de sécurité : Seul le propriétaire peut éditer
        if ($video->user_id !== auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        $categories = Category::all();
        return view('admin.videos.edit', compact('video', 'categories'));
    }

    public function update(Request $request, Video $video)
    {
        if ($video->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'level' => 'required|string', // On ajoute la validation ici
        ]);

        $video->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'level' => $request->level, // Maintenant, ce ne sera plus NULL
        ]);

        return redirect()->route('admin.videos.index')->with('success', 'Vidéo mise à jour !');
    }

    public function destroy(Video $video)
    {
        $video->delete(); // Grâce à SoftDeletes, elle n'est pas vraiment supprimée du disque dur
        return redirect()->route('admin.videos.index')->with('success', 'Vidéo supprimée.');
    }
}
