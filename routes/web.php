<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\TopHeadLineController;
use App\Http\Controllers\UserSettingController;
use App\Http\Controllers\UserFavouriteController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/paginated', [ArticleController::class, 'paginated'])->name('articles.paginated');

    Route::get('/topHeadlines', [TopHeadLineController::class, 'index'])->name('topHeadlines.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/userSettings', [UserSettingController::class, 'edit'])->name('userSettings.edit');
    Route::patch('/userSettings', [UserSettingController::class, 'update'])->name('userSettings.update');

    Route::get('/favourites', [UserFavouriteController::class, 'index'])->name('favourites.index');
    Route::post('/favourites', [UserFavouriteController::class, 'store'])->name('favourites.store');
    
});

require __DIR__.'/auth.php';
