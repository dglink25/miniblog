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


    // routes/web.php
    Route::middleware('auth')->group(function () {
        Route::post('/articles/{article}/favorite', [\App\Http\Controllers\FavoriteController::class,'toggle'])
            ->name('favorites.toggle');
        Route::get('/mes-favoris', [\App\Http\Controllers\FavoriteController::class,'index'])
            ->name('favorites.index');
    });

Route::middleware('auth')->get('/notifications/poll', function () {
    $count = auth()->user()->unreadNotifications()->count();
    return response()->json(['unread' => $count]);
})->name('notifications.poll');

Route::middleware('auth')->post('/notifications/mark-all-read', function () {
    auth()->user()->unreadNotifications->markAsRead();
    return back();
})->name('notifications.readAll');

Route::get('/user/{user}/articles', [\App\Http\Controllers\ArticleController::class,'byUser'])->name('user.articles');

Route::post('/editor/upload', [\App\Http\Controllers\EditorUploadController::class,'store'])
    ->middleware('auth')->name('tinymce.upload');

    
Route::get('/mes-articles', [\App\Http\Controllers\ArticleController::class,'mine'])
    ->middleware('auth')->name('articles.mine');

Route::delete('/admin/articles/{article}', [\App\Http\Controllers\Admin\AdminController::class,'destroy'])
    ->middleware('auth') // 👈 enlève 'admin'
    ->name('admin.articles.destroy');

Route::get('/bienvenue', function () {
    $settings = \App\Models\SiteSetting::current();
    return view('intro', compact('settings'));
})->name('intro.show');

Route::post('/bienvenue/ok', function(){
    return redirect()->route('articles.index')->withCookie(cookie()->forever('seen_intro', now()->toDateTimeString()));
})->name('intro.accept');

Route::get('/notifications/{id}', [App\Http\Controllers\NotificationController::class, 'show'])
    ->name('notifications.show');

Route::post('/tinymce/upload', [\App\Http\Controllers\TinyMCEController::class, 'upload'])->name('tinymce.upload');

Route::patch('/admin/articles/{article}/pin', [AdminController::class, 'togglePin'])
    ->middleware('auth') // garde juste 'auth'
    ->name('admin.articles.togglePin');

