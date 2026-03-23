<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        // L'erreur Intelephense devrait disparaître ici
        $favoriteVideos = $user->likedVideos()
            ->with(['category', 'user'])
            ->latest()
            ->get();

        return view('dashboard', compact('favoriteVideos'));
    }
}
