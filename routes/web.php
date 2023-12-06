<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\TopHeadLineController;
use App\Http\Controllers\UserSettingController;
use App\Http\Controllers\UserFavouriteController;
use App\Http\Controllers\UserCommentController;
use App\Http\Controllers\AdminController;
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
    Route::delete('/favourites/{favourite_id}', [UserFavouriteController::class, 'destroy'])->name('favourites.destroy');
    
    Route::post('/comments', [UserCommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment_id}', [UserCommentController::class, 'destroy'])->name('comments.destroy');
});

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/adminDashboard', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/{userId}',[AdminController::class, 'show'])->name('admin.show');
    Route::get('/admin/comments/{comment_id}/edit', [AdminController::class, 'edit'])->name('comments.edit');
    Route::patch('/admin/comments/{comment_id}', [AdminController::class, 'update'])->name('comments.update');

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');
});

require __DIR__.'/auth.php';
