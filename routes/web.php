<?php

use Illuminate\Support\Facades\Route;

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
use App\Http\Controllers\Admin\PlanController as AdminPlanController;
use App\Http\Controllers\Admin\SubscriptionAdminController;
use App\Http\Controllers\EditorUploadController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TinyMCEController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\UserArticleController;


Route::get('/googleb4554f24cec51de9.html', function () {
    return response('google-site-verification: googleb4554f24cec51de9.html', 200)
        ->header('Content-Type', 'text/plain');
});




Route::get('/admin/settings/edit', [SettingController::class, 'edit'])->name('admin.settings.edit');
Route::post('/admin/settings/update', [SettingController::class, 'update'])->name('admin.settings.update');


Route::get('/utilisateur/{user}/articles', [UserArticleController::class, 'index'])
    ->name('user.article');

Route::post('/annonces/{annonce}/dismiss', [AnnouncementController::class, 'dismiss'])
    ->name('annonces.dismiss');

Route::resource('annonces', \App\Http\Controllers\Admin\AnnouncementController::class);

Route::get('/abonnements', [Subscription_Controller::class,'plans'])->name('subscriptions.plans');
Route::get('/mes-paiements', [PaymentController::class,'history'])->name('payments.history');
Route::get('/boost/success/{article}', [PaymentController::class,'boostSuccess'])->name('boost.success'); // créer cette méthode
Route::get('/user/{user}/articles', [ArticleController::class, 'byUser'])->name('user.articles');

Route::post('/kiapay/callback', [PaymentController::class, 'kiapayCallback'])->name('kiapay.callback');
Route::get('/kiapay/return', [PaymentController::class, 'kiapayReturn'])->name('kiapay.return');

Route::get('admin/subscriptions', [SubscriptionAdminController::class,'index'])
    ->name('admin.subscriptions.index');

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('subscriptions', [SubscriptionAdminController::class, 'index'])
        ->name('admin.subscriptions.index');
});


Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('dashboard');

    // Plans
    Route::resource('plans', App\Http\Controllers\Admin\PlanController::class);

    // Utilisateurs
    Route::resource('users', UserController::class);

    // Articles (validés/en attente)
    Route::resource('articles', ArticleController::class);
});




// -----------------------------
// PAGE D’ACCUEIL
// -----------------------------
Route::get('/', fn() => redirect()->route('articles.index'));

// -----------------------------
// ARTICLES
// -----------------------------
Route::resource('articles', ArticleController::class)
    ->parameters(['articles' => 'article:slug']);

Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');

Route::post('/comments/{comment}/react', [CommentController::class, 'react'])->name('comments.react');

// Modifier un commentaire
Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');

    // Supprimer un commentaire
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');


Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function(){
    Route::resource('annonces', AdminAnnouncementController::class)->except(['show']);
    Route::post('annonces/{announcement}/publish-toggle', [AdminAnnouncementController::class,'toggle'])
        ->name('annonces.toggle');
});

// Bloc admin
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function(){
    // Ressource annonces
    Route::resource('annonces', AdminAnnouncementController::class)->except(['show']);

    // Toggle publish pour annonces
    Route::post('annonces/{announcement}/publish-toggle', [AdminAnnouncementController::class,'toggle'])
        ->name('annonces.toggle');

    // Plans d'abonnement
    Route::resource('plans', AdminPlanController::class)->except(['show']);
});

Route::get('/user/{user}/articles', [ArticleController::class,'byUser'])->name('user.articles');
Route::post('/articles/{article:slug}/rate', [ArticleRatingController::class,'store'])->name('articles.rate');
Route::middleware('auth')->group(function () {
    Route::get('/mes-articles', [ArticleController::class,'mine'])->name('articles.mine');
    Route::get('/articles/create', [ArticleController::class,'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class,'store'])->name('articles.store');
    Route::post('/articles/{article}/comments', [CommentController::class,'store'])->name('articles.comments.store');
});

// -----------------------------
// NOTES GLOBALES (site rating)
// -----------------------------
Route::post('/ratings', [SiteRatingController::class,'store'])->name('ratings.store');

// -----------------------------
// SUIVI (Follow / Unfollow)
// -----------------------------
Route::post('/users/{user}/follow', [SubscriptionController::class,'follow'])->name('users.follow');
Route::delete('/users/{user}/unfollow', [SubscriptionController::class,'unfollow'])->name('users.unfollow');

// -----------------------------
// FAVORIS
// -----------------------------
Route::middleware('auth')->group(function () {
    Route::post('/articles/{article}/favorite', [FavoriteController::class,'toggle'])->name('favorites.toggle');
    Route::get('/mes-favoris', [FavoriteController::class,'index'])->name('favorites.index');
});

// -----------------------------
// NOTIFICATIONS
// -----------------------------
Route::middleware('auth')->group(function () {
    Route::get('/notifications/poll', fn() => response()->json(['unread' => auth()->user()->unreadNotifications()->count()]))
        ->name('notifications.poll');
    Route::post('/notifications/mark-all-read', fn() => tap(auth()->user()->unreadNotifications)->markAsRead() && back())
        ->name('notifications.readAll');
    Route::get('/notifications/unread-count', [NotificationController::class,'unreadCount'])->name('notifications.unreadCount');
    Route::get('/notifications/{id}', [NotificationController::class,'show'])->name('notifications.show');
});

// -----------------------------
// UPLOAD (TinyMCE, Editor…)
// -----------------------------
Route::middleware('auth')->group(function () {
    Route::post('/editor/upload', [EditorUploadController::class,'store'])->name('tinymce.upload');
    Route::post('/tinymce/upload', [TinyMCEController::class,'upload'])->name('tinymce.upload.alt');
});

// -----------------------------
// MEDIA
// -----------------------------
Route::delete('/media/{media}', [MediaController::class,'destroy'])->name('media.destroy');

// -----------------------------
// INTRO / BIENVENUE
// -----------------------------
Route::get('/bienvenue', function () {
    $settings = \App\Models\SiteSetting::current();
    return view('intro', compact('settings'));
})->name('intro.show');
Route::post('/bienvenue/ok', fn() => redirect()->route('articles.index')->withCookie(cookie()->forever('seen_intro', now()->toDateTimeString())))->name('intro.accept');

// -----------------------------
// ADMIN (Général)
// -----------------------------
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [AdminController::class,'dashboard'])->name('admin.dashboard');
    Route::post('/settings/toggle-auto-publish', [AdminController::class,'toggleAutoPublish'])->name('admin.toggleAutoPublish');
    Route::post('/settings/intro', [AdminController::class,'updateIntro'])->name('admin.updateIntro');
    Route::post('/articles/{article}/approve', [AdminController::class,'approve'])->name('admin.articles.approve');
    Route::post('/articles/{article}/reject', [AdminController::class,'reject'])->name('admin.articles.reject');
    Route::delete('/articles/{article}', [AdminController::class,'destroy'])->name('admin.articles.destroy');
    Route::patch('/articles/{article}/pin', [AdminController::class,'togglePin'])->name('admin.articles.togglePin');
});


// -----------------------------
// ANNONCES (ADMIN)
// -----------------------------
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function() {
    // Ressource annonces
    Route::resource('annonces', AdminAnnouncementController::class)->except(['show']);

    // Toggle publish pour annonces
    Route::post('annonces/{announcement}/publish-toggle', [AdminAnnouncementController::class,'toggle'])
        ->name('annonces.toggle');
});

// -----------------------------
// SUGGESTIONS
// -----------------------------
Route::middleware('auth')->group(function () {
    Route::resource('suggestions', SuggestionController::class)->only(['create','store']);
});
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/suggestions', [SuggestionAdminController::class,'index'])->name('admin.suggestions.index');
    Route::get('/suggestions/{suggestion}', [SuggestionAdminController::class,'show'])->name('admin.suggestions.show');
    Route::match(['post','put'],'/suggestions/{suggestion}/status', [SuggestionAdminController::class,'updateStatus'])->name('admin.suggestions.updateStatus');
});

// -----------------------------
// ABONNEMENTS
// -----------------------------
Route::get('/abonnements', [Subscription_Controller::class,'plans'])->name('subscriptions.plans');
Route::middleware('auth')->group(function () {
    Route::post('/abonnements/checkout/{plan}', [Subscription_Controller::class,'checkout'])->name('subscriptions.checkout');
    Route::get('/abonnements/verification', [Subscription_Controller::class,'verifyForm'])->name('subscriptions.verifyForm');
    Route::post('/abonnements/verification', [Subscription_Controller::class,'verifyCode'])->name('subscriptions.verifyCode');
});
Route::post('/fedapay/callback', [Subscription_Controller::class,'fedapayCallback'])->name('fedapay.callback');

// -----------------------------
// ADMIN : PLANS & SUBSCRIPTIONS
// -----------------------------
Route::middleware(['auth','can:admin'])->group(function () {
    Route::resource('admin/plans', AdminPlanController::class)->names('admin.plans');
    Route::get('admin/subscriptions', [SubscriptionAdminController::class,'index'])->name('admin.subscriptions.index');
    Route::post('admin/subscriptions/{subscription}/mark-paid', [SubscriptionAdminController::class,'markPaidAndSendCode'])->name('admin.subscriptions.markPaid');
    Route::post('admin/subscriptions/{subscription}/activate', [SubscriptionAdminController::class,'activate'])->name('admin.subscriptions.activate');
    Route::get('admin/subscriptions/grant', [SubscriptionAdminController::class,'grantForm'])->name('admin.subscriptions.grantForm');
    Route::post('admin/subscriptions/grant', [SubscriptionAdminController::class,'grantStore'])->name('admin.subscriptions.grantStore');
});

// -----------------------------
// DASHBOARD
// -----------------------------
Route::get('/dashboard', fn() => view('dashboard'))->middleware('auth')->name('dashboard');

// HISTORIQUES PAYEMENTS
Route::get('/mes-paiements', [PaymentController::class, 'history'])->name('payments.history');

Route::post('/articles/{article}/react', [ArticleController::class,'react'])->name('articles.react');
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/settings', [SettingController::class, 'edit'])->name('admin.settings.edit');
    Route::post('/admin/settings', [SettingController::class, 'update'])->name('admin.settings.update');
});
Route::get('/test-error', function () {
    abort(500);
});

