<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Video $video)
    {
        $request->validate([
            'content' => 'required|string|min:3|max:1000',
        ]);

        $video->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->input('content'), // Cible spécifiquement le champ du formulaire
        ]);

        return back()->with('success', 'Votre commentaire a été publié !');
    }
}
