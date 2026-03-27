<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\User;
use App\Notifications\NewVideoPublished;
use Illuminate\Support\Facades\Notification;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class VideoController extends Controller
{

    public function create()
    {
        $categories = Category::all();
        return view('videos.create', compact('categories'));
    }


    public function store(Request $request)
    {
        // 1. Validation STRICTE d'abord (On ne dépense pas de bande passante si le titre est vide)
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'video' => 'required|mimes:mp4,mov,ogg,qt|max:50000', // Augmenté à 50Mo pour le Cloud
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'level' => 'required'
        ]);

        // 2. Upload vers Cloudinary (Vidéo ET Miniature)
        // On utilise Cloudinary pour les deux afin d'avoir un site rapide partout
        $cloudinaryVideo = Cloudinary::uploadVideo($request->file('video')->getRealPath(), [
            'folder' => 'tutos/videos',
        ]);
        
        $cloudinaryThumbnail = Cloudinary::upload($request->file('thumbnail')->getRealPath(), [
            'folder' => 'tutos/thumbnails',
        ]);

        // 3. Création de l'enregistrement en base de données
        $video = Video::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . rand(100, 999),
            'description' => $request->description,
            'video_url' => $cloudinaryVideo->getSecurePath(), // URL Cloudinary stable
            'thumbnail_url' => $cloudinaryThumbnail->getSecurePath(), // URL Cloudinary stable
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),
            'level' => $request->level,
            'is_published' => true
        ]);

        // 4. Notification aux autres utilisateurs
        // Génie Logiciel : On récupère les IDs pour être plus performant sur les grosses listes
        $otherUsers = User::where('id', '!=', auth()->id())->get();
        Notification::send($otherUsers, new NewVideoPublished($video));

        return redirect()->route('dashboard')->with('success', 'La vidéo "' . $video->title . '" est en ligne sur le Cloud !');
    }
    public function index(Request $request): View|RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // 1. Redirection Prioritaire : Si l'utilisateur est admin, on l'envoie vers l'interface de gestion
        if ($user && $user->role === 'admin') {
            return redirect()->route('admin.videos.index');
        }
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

   public function toggleLike(Video $video)
    {
        // Sécurité supplémentaire : si l'utilisateur n'est pas connecté, on redirige
        if (!auth()->check()) {
            return redirect()->route('login')->with('info', 'Vous devez être connecté pour aimer une vidéo.');
        }

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->likedVideos()->toggle($video->id);

        return back();
    }

    /**
     * Affiche le formulaire de modification.
     */
    public function edit(Video $video): View
    {
        // Vérification de sécurité : Seul l'auteur peut modifier
        if (auth()->id() !== $video->user_id) {
            abort(403, 'Action non autorisée.');
        }

        $categories = \App\Models\Category::all();
        return view('videos.edit', compact('video', 'categories'));
    }

    /**
     * Met à jour la vidéo dans la base de données.
     */
    public function update(Request $request, Video $video)
    {
        if (auth()->id() !== $video->user_id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'level' => 'required|in:débutant,intermédiaire,avancé',
        ]);

        $video->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'level' => $request->level,
            'is_published' => $request->has('is_published'), // Case à cocher
            // Le slug se mettra à jour via ton observer ou manuellement si nécessaire
        ]);

        return redirect()->route('dashboard')->with('success', 'Tutoriel mis à jour avec succès !');
    }

    /**
     * Supprime la vidéo de la base de données et du stockage physique.
     */
    public function destroy(Video $video)
    {
        if (auth()->id() !== $video->user_id) {
            abort(403);
        }

        // 1. Suppression des fichiers physiques sur le disque
        // On extrait le chemin relatif (ex: tutos/nom_video.mp4)
        if ($video->video_url) {
            $path = str_replace('/storage/', '', $video->video_url);
            Storage::disk('public')->delete($path);
        }

        if ($video->thumbnail_url) {
            $thumbPath = str_replace('/storage/', '', $video->thumbnail_url);
            Storage::disk('public')->delete($thumbPath);
        }

        // 2. Suppression de l'enregistrement en base de données
        $video->delete();
        return redirect()->route('dashboard')->with('success', 'Tutoriel supprimé définitivement.');
    }

}