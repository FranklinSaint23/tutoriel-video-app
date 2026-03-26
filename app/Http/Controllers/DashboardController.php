<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        // 1. Vidéos que J'AI uploadées (Relation simple HasMany)
        $myVideos = \App\Models\Video::where('user_id', $user->id)->latest()->get();

        // 2. Vidéos que J'AI likées (Relation BelongsToMany via table pivot)
        $favoriteVideos = $user->likedVideos()->with(['category', 'user'])->latest()->get();

        return view('dashboard', compact('myVideos', 'favoriteVideos'));
    }
    
}
