<?php

require __DIR__.'/auth.php';
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ArticleRatingController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SiteRatingController;


Route::post('/ratings', [SiteRatingController::class,'store'])->name('ratings.store');


// Page d\'accueil
Route::get('/', fn()=>redirect()->route('articles.index'));

// Articles existants (slug)
Route::resource('articles', ArticleController::class)
  ->parameters(['articles' => 'article:slug']);

// Page auteur
Route::get('/user/{user}/articles', [ArticleController::class,'byUser'])->name('user.articles');

// Notation d\'article
Route::post('/articles/{article:slug}/rate', [ArticleRatingController::class,'store'])->name('articles.rate');

// Follow / Unfollow
Route::post('/users/{user}/follow', [SubscriptionController::class,'follow'])->name('users.follow');
Route::delete('/users/{user}/unfollow', [SubscriptionController::class,'unfollow'])->name('users.unfollow');

// Mes publications (par statut)
Route::get('/mes-articles', [ArticleController::class,'mine'])->middleware('auth')->name('articles.mine');

Route::prefix('admin')->middleware('auth')->group(function(){
    Route::get('/', [AdminController::class,'dashboard'])->name('admin.dashboard');
    Route::post('/settings/toggle-auto-publish', [AdminController::class,'toggleAutoPublish'])->name('admin.toggleAutoPublish');
    Route::post('/settings/intro', [AdminController::class,'updateIntro'])->name('admin.updateIntro');
    Route::post('/articles/{article}/approve', [AdminController::class,'approve'])->name('admin.articles.approve');
    Route::post('/articles/{article}/reject', [AdminController::class,'reject'])->name('admin.articles.reject');
});

Route::get('/dashboard', function () {
    return view('dashboard'); // Crée ensuite la vue resources/views/dashboard.blade.php
})->middleware(['auth'])->name('dashboard');


Route::post('/editor/upload', [\App\Http\Controllers\EditorUploadController::class,'store'])->name('tinymce.upload')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::resource('suggestions', SuggestionController::class);
});


Route::post('/articles/{article}/comments', [CommentController::class, 'store'])
    ->middleware('auth')
    ->name('articles.comments.store');
