<?php

use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VideoController; // Le contrôleur public
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\NotificationController;


// --- PARTIE ADMIN (Seulement si connecté) ---
// --- PARTIE ADMIN (Strictement réservée aux Admins) ---
Route::middleware(['auth', 'can:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/videos', [AdminVideoController::class, 'index'])->name('videos.index');
    Route::get('/videos/create', [AdminVideoController::class, 'create'])->name('videos.create');
    Route::post('/videos', [AdminVideoController::class, 'store'])->name('videos.store');
    Route::get('/videos/{video}/edit', [AdminVideoController::class, 'edit'])->name('videos.edit');
    Route::put('/videos/{video}', [AdminVideoController::class, 'update'])->name('videos.update');
    Route::delete('/videos/{video}', [AdminVideoController::class, 'destroy'])->name('videos.destroy');
});

// --- ROUTES BREEZE ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard'); 

Route::middleware(['auth'])->group(function () {
    Route::post('/videos/{video}/like', [VideoController::class, 'toggleLike'])->name('videos.like');
    Route::get('/', [VideoController::class, 'index'])->name('videos.index');
    Route::get('/watch/{slug}', [VideoController::class, 'show'])->name('videos.show');
    Route::get('/videos/{video}/edit', [VideoController::class, 'edit'])->name('videos.edit');
    Route::put('/videos/{video}', [VideoController::class, 'update'])->name('videos.update');
    Route::delete('/videos/{video}', [VideoController::class, 'destroy'])->name('videos.destroy');
    Route::get('/videos/create', [VideoController::class, 'create'])->name('videos.create');
    Route::post('/videos', [VideoController::class, 'store'])->name('videos.store');
    Route::post('/videos/{video}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/videos/{video}/rate', [RatingController::class, 'store'])->name('videos.rate');
});


Route::middleware(['auth'])->group(function () {
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::post('/playlists', [PlaylistController::class, 'store'])
        ->name('playlists.store');
    Route::post('/playlists/{playlist}/add-video/{video}', [PlaylistController::class, 'addVideo'])
        ->name('playlists.add');
    Route::get('/my-playlists', [PlaylistController::class, 'index'])
        ->name('playlists.index');
    Route::get('/playlists/{playlist}', [PlaylistController::class, 'show'])
        ->name('playlists.view');
    Route::get('/my-playlists', [PlaylistController::class, 'index'])->name('playlists.index');
    Route::get('/playlists/{playlist}', [PlaylistController::class, 'show'])->name('playlists.view');
    Route::delete('/playlists/{playlist}', [PlaylistController::class, 'destroy'])->name('playlists.destroy');
    Route::delete('/playlists/{playlist}/video/{video}', [PlaylistController::class, 'removeVideo'])->name('playlists.remove-video');
});



use App\Models\Video;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;

Route::get('/setup-fake-videos', function () {
    // 1. Vérifier qu'on a au moins un utilisateur et une catégorie
    $admin = User::first();
    $category = Category::first();

    if (!$admin || !$category) {
        return "Erreur : Crée d'abord l'admin et les catégories via /setup-categories !";
    }

    // 2. Liste de vidéos de test (URLs publiques stables)
    $fakeVideos = [
        [
            'title' => 'Apprendre Laravel en 5 minutes',
            'url' => 'https://res.cloudinary.com/demo/video/upload/elephants.mp4',
            'thumb' => 'https://res.cloudinary.com/demo/video/upload/elephants.jpg'
        ],
        [
            'title' => 'Installation de Docker sur Ubuntu',
            'url' => 'https://www.w3schools.com/html/mov_bbb.mp4',
            'thumb' => 'https://www.w3schools.com/html/images/w3html5.gif'
        ],
        [
            'title' => 'Introduction à la CyberSécurité',
            'url' => 'https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4',
            'thumb' => 'https://peach.blender.org/wp-content/uploads/title_an_01.jpg'
        ]
    ];

    $count = 0;
    foreach ($fakeVideos as $data) {
        Video::updateOrCreate(
            ['title' => $data['title']],
            [
                'slug' => Str::slug($data['title']) . '-' . rand(100, 999),
                'description' => "Ceci est une vidéo de test pour la plateforme " . $data['title'],
                'video_url' => $data['url'],
                'thumbnail_url' => $data['thumb'],
                'category_id' => $category->id,
                'user_id' => $admin->id,
                'level' => 'Débutant',
                'is_published' => true
            ]
        );
        $count++;
    }

    return "Succès : " . $count . " vidéos de test ont été ajoutées !";
});