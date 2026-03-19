<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/




Route::get('/', [VideoController::class, 'index'])->name('videos.index');
Route::get('/watch/{slug}', [VideoController::class, 'show'])->name('videos.show');


Route::get('/admin/videos/create', [AdminVideoController::class, 'create'])->name('admin.videos.create');
Route::post('/admin/videos', [AdminVideoController::class, 'store'])->name('admin.videos.store');