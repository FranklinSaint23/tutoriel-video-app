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



use App\Models\Category;
use Illuminate\Support\Str;

Route::get('/setup', function () {
    // Une liste exhaustive pour couvrir tous les aspects de tes tutoriels
    $categories = [
        // Développement Web
        'Laravel Framework',
        'Frontend (Vue.js / React)',
        'Tailwind CSS',
        'JavaScript Moderne',
        
        // Mobile & Desktop
        'Développement Mobile (Flutter)',
        'Desktop Apps (Electron)',
        
        // Backend & Infrastructure
        'Node.js & API',
        'Docker & Kubernetes',
        'Déploiement Cloud (AWS/GCP)',
        'CI/CD & GitHub Actions',
        
        // Sécurité & Systèmes
        'Hacking Éthique',
        'Administration Linux (Ubuntu)',
        'Sécurité des Réseaux',
        'Audit de Code PHP',
        
        // Data & IA
        'Python pour la Data',
        'Intelligence Artificielle',
        'Bases de Données (PostgreSQL/NoSQL)'
    ];

    $count = 0;
    foreach ($categories as $name) {
        Category::updateOrCreate(
            ['name' => $name], // Recherche par nom
            ['slug' => Str::slug($name)] // Crée ou met à jour le slug
        );
        $count++;
    }

    return "Félicitations ! " . $count . " catégories professionnelles sont maintenant prêtes dans ta base de données.";
});