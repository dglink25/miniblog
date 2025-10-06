<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\SiteSetting;
use App\Notifications\ArticleApprovedNotification;
use App\Notifications\ArticleStatusNotification;
use Illuminate\Http\Request;
use App\Notifications\ArticleRejectedNotification;
use App\Notifications\FollowedAuthorPublishedNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\ArticleRejectedMail;

class AdminController extends Controller{
    public function __construct(){
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || !auth()->user()->is_admin) {
                abort(403, 'Accès refusé');
            }
            return $next($request);
        });
    }

    public function dashboard(){
        $pending = Article::pending()->with('user')->latest()->paginate(10);
        $validated = Article::published()->with('user')->latest('published_at')->paginate(10);
        $rejected = Article::rejected()->with('user')->latest()->paginate(10);
        $settings = SiteSetting::current();
        $latestArticle = Article::latest()->first(); // ou récupère l'article que tu veux par défaut
        return view('admin.dashboard', compact('pending','validated','rejected','settings', 'latestArticle'));

        //return view('admin.dashboard', compact('pending','validated','rejected','settings'));
    }

    public function toggleAutoPublish(Request $request){
        $s = SiteSetting::current();
        $s->update(['auto_publish' => !$s->auto_publish]);
        return back()->with('success','Paramètre mis à jour.');
    }

    public function updateIntro(Request $request){
        $data = $request->validate(['intro_html' => 'required|string']);
        SiteSetting::current()->update(['intro_html'=>$data['intro_html']]);
        return back()->with('success','Contenu d\'intro mis à jour.');
    }

    public function approve(Article $article){
        $article->forceFill([
            'status' => 'validated',
            'rejection_reason' => null,
            'published_at' => now(),
            'is_published' => true
        ])->save();

        // Notifier l'auteur
        $article->user->notify(new ArticleStatusNotification($article, 'validé'));
        $article->user->notify(new ArticleApprovedNotification($article));
        // Notifier les abonnés
        foreach ($article->user->followers as $follower) {
            $follower->notify(new ArticleApprovedNotification($article));
        }

        return back()->with('success','Article validé.');

    }

    public function reject(Request $request, Article $article){
        $data = $request->validate(['reason' => 'required|string|min:1']);
        $article->update(['status'=>'rejected','rejection_reason'=>$data['reason'],'is_published'=>false]);

        $article->user->notify(new ArticleStatusNotification($article, 'rejeté', $data['reason']));
        Mail::to($article->user->email)
            ->send(new ArticleRejectedMail($article, $data['reason']));
        //Mail::to($article->user->email)
            //->send(new ArticleStatusNotification($article, 'rejeté', $request->reason));
        
        return back()->with('success','Article rejeté.');
    }
    public function destroy(Article $article){
        $article->delete();

        // Recharger les données nécessaires
        $pending   = Article::pending()->with('user')->latest()->paginate(10);
        $validated = Article::published()->with('user')->latest('published_at')->paginate(10);
        $rejected  = Article::rejected()->with('user')->latest()->paginate(10);
        $settings  = SiteSetting::current();

        return view('admin.dashboard', compact('pending','validated','rejected','settings'))
            ->with('success', 'Article supprimé avec succès');
    }

    public function togglePin(Article $article){
        $article->pinned = !$article->pinned;
        $article->save();

        return back()->with('success', $article->pinned ? 'Article épinglé.' : 'Article désépinglé.');
    }
    public function index(){
        if (!auth()->user() || !auth()->user()->is_admin) {
            abort(403, 'Accès interdit');
        }

        $users = User::all();
        return view('admin.users.index', compact('users'));
    }




}


