<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, Video $video)
    {
        $request->validate([
            'stars' => 'required|integer|min:1|max:5',
        ]);

        // Génie Logiciel : On met à jour si ça existe déjà, sinon on crée
        Rating::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'video_id' => $video->id,
            ],
            [
                'stars' => $request->stars,
            ]
        );

        return back()->with('success', 'Merci pour votre note !');
    }
}