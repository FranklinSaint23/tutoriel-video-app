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
}