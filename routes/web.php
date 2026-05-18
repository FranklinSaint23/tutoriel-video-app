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

use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

Route::get('/setup-demo-data', function () {
    try {
        // 1. Création du Super Utilisateur
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin1234'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Liste des 10 vidéos
        $videos = [
            ['title' => 'Big Buck Bunny', 'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4', 'description' => 'Animation classique de lapin.'],
            ['title' => 'Elephant Dream', 'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4', 'description' => 'Premier projet de film ouvert avec Blender.'],
            ['title' => 'For Bigger Blazes', 'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4', 'description' => 'Vidéo de démonstration technologique.'],
            ['title' => 'For Bigger Escapes', 'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4', 'description' => 'Extrait vidéo publicitaire chromecast.'],
            ['title' => 'For Bigger Fun', 'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4', 'description' => 'Animation amusante et colorée.'],
            ['title' => 'For Bigger Joyrides', 'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4', 'description' => 'Test de lecture vidéo rapide.'],
            ['title' => 'For Bigger Meltdowns', 'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerMeltdowns.mp4', 'description' => 'Démonstration de flux vidéo fluide.'],
            ['title' => 'Subaru Outbacks', 'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/SubaruOutbackOnStreetAndDirt.mp4', 'description' => 'Voiture sur route et terre.'],
            ['title' => 'Tears of Steel', 'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/TearsOfSteel.mp4', 'description' => 'Film de science-fiction open-source.'],
            ['title' => 'We Are Going On Bullrun', 'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/WeAreGoingOnBullrun.mp4', 'description' => 'Course automobile de test.'],
        ];

        foreach ($videos as $video) {
            Video::updateOrCreate(
                ['title' => $video['title']],
                [
                    'slug' => Str::slug($video['title']),
                    'description' => $video['description'],
                    'video_url' => $video['url'], // Corrigé ici avec le bon nom de colonne !
                ]
            );
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Super Admin et 10 vidéos injectés avec succès sur PostgreSQL !'
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Erreur lors de l\'injection : ' . $e->getMessage()
        ], 500);
    }
});