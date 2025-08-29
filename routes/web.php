<?php

require __DIR__.'/auth.php';
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Subscription_Controller;
use App\Http\Controllers\ArticleRatingController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SiteRatingController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\AnnouncementPublicController;
use App\Http\Controllers\Admin\SuggestionAdminController;
use App\Http\Controllers\Admin\PlanController;

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

Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');


// Annonces (admin)
Route::prefix('admin')->middleware(['auth'])->group(function(){
    Route::resource('annonces', AdminAnnouncementController::class)->except(['show']);
    Route::post('annonces/{announcement}/publish-toggle', [AdminAnnouncementController::class,'toggle'])
        ->name('admin.annonces.toggle');
});

// Annonces (public/dismiss)
Route::post('/annonces/{announcement}/dismiss', [AnnouncementPublicController::class,'dismiss'])
    ->middleware('auth')
    ->name('annonces.dismiss');

Route::middleware('auth')->group(function(){
    Route::get('/suggestions/create', [SuggestionController::class,'create'])->name('suggestions.create');
    Route::post('/suggestions', [SuggestionController::class,'store'])->name('suggestions.store');
});

// admin
Route::prefix('admin')->middleware(['auth'])->group(function(){
    Route::get('/suggestions', [SuggestionAdminController::class,'index'])->name('admin.suggestions.index');
    Route::get('/suggestions/{suggestion}', [SuggestionAdminController::class,'show'])->name('admin.suggestions.show');
    Route::post('/suggestions/{suggestion}/status', [SuggestionAdminController::class,'updateStatus'])
        ->name('admin.suggestions.updateStatus');

    Route::put('suggestions/{suggestion}', [SuggestionAdminController::class,'updateStatus'])
        ->name('admin.suggestions.updateStatus');

});


Route::middleware(['auth'])->group(function(){
    Route::get('/articles/create', [ArticleController::class,'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class,'store'])->name('articles.store');
});

Route::get('/abonnements', [Subscription_Controller::class,'plans'])->name('subscriptions.plans');
Route::post('/abonnements/checkout/{plan}', [Subscription_Controller::class,'checkout'])
    ->middleware('auth')->name('subscriptions.checkout');
Route::get('/abonnements/verification', [Subscription_Controller::class,'verifyForm'])
    ->middleware('auth')->name('subscriptions.verifyForm');
Route::post('/abonnements/verification', [Subscription_Controller::class,'verifyCode'])
    ->middleware('auth')->name('subscriptions.verifyCode');

Route::post('/fedapay/callback', [Subscription_Controller::class,'fedapayCallback'])
    ->name('fedapay.callback');

Route::prefix('admin')->middleware(['auth'])->group(function(){
    Route::resource('plans', PlanController::class)->except(['show']);
});

Route::get('/notifications/unread-count', [\App\Http\Controllers\NotificationController::class,'unreadCount'])
    ->middleware('auth')->name('notifications.unreadCount');
Route::get('/notifications/{id}', [\App\Http\Controllers\NotificationController::class,'show'])
    ->middleware('auth')->name('notifications.show');
