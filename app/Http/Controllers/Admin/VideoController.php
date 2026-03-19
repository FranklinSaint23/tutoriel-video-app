<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            'user_id' => 1, // À remplacer par auth()->id() plus tard
            'level' => $request->level,
            'is_published' => true
        ]);

        return redirect()->route('videos.index')->with('success', 'Vidéo publiée avec succès !');
    }
}
