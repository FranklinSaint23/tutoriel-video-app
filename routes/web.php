<?php

use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VideoController; // Le contrôleur public
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\PlaylistController;



// --- PARTIE PUBLIQUE (Accessible par tous) ---


// --- PARTIE ADMIN (Seulement si connecté) ---
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
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
Route::post('/videos/{video}/like', [VideoController::class, 'toggleLike'])->name('videos.like')->middleware('auth');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard'); // <-- C'est ce nom qui manquait !

Route::middleware(['auth'])->group(function () {
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


// On protège ces routes avec le middleware 'auth' car seul un utilisateur connecté 
// peut gérer ses propres playlists.
Route::middleware(['auth'])->group(function () {

    // 1. Créer une nouvelle playlist (et y ajouter une vidéo si video_id est présent)
    Route::post('/playlists', [PlaylistController::class, 'store'])
        ->name('playlists.store');

    // 2. Ajouter une vidéo spécifique à une playlist existante
    // On utilise le "Route Model Binding" pour récupérer automatiquement 
    // l'objet Playlist et l'objet Video via leurs IDs.
    Route::post('/playlists/{playlist}/add-video/{video}', [PlaylistController::class, 'addVideo'])
        ->name('playlists.add');

    // 3. (Optionnel) Voir toutes ses playlists
    Route::get('/my-playlists', [PlaylistController::class, 'index'])
        ->name('playlists.index');
        
    // 4. (Optionnel) Voir le contenu d'une playlist spécifique
    Route::get('/playlists/{playlist}', [PlaylistController::class, 'show'])
        ->name('playlists.view');
});