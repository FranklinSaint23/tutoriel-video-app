<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(): View
    {
        // Récupère les vidéos publiées avec leurs catégories, 6 par page
        $videos = Video::with('category')
            ->where('is_published', true)
            ->latest()
            ->paginate(6);

        return view('videos.index', compact('videos'));
    }
}