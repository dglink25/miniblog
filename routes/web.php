<?php

require __DIR__.'/auth.php'; // Breeze gère automatiquement les routes login/register/logout/etc.

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Redirection racine
Route::get('/', function () {
    return redirect()->route('articles.index');
});

// Dashboard / profile (Breeze fournit déjà auth middleware)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Articles avec slug comme clé
Route::resource('articles', ArticleController::class)
    ->parameters(['articles' => 'article:slug']);

// Commentaires (store uniquement), article résolu par slug
Route::post('articles/{article:slug}/comments', [CommentController::class, 'store'])
    ->name('articles.comments.store');

Route::delete('media/{media}', [MediaController::class, 'destroy'])
    ->name('media.destroy')
    ->middleware('auth');

