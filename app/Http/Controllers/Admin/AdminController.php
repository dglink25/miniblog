<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\SiteSetting;
use App\Notifications\ArticleApprovedNotification;
use App\Notifications\ArticleStatusNotification;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || !auth()->user()->is_admin) {
                abort(403, 'Accès refusé');
            }
            return $next($request);
        });
    }



    public function dashboard()
    {
        $pending = Article::pending()->with('user')->latest()->paginate(10);
        $validated = Article::published()->with('user')->latest('published_at')->paginate(10);
        $rejected = Article::rejected()->with('user')->latest()->paginate(10);
        $settings = SiteSetting::current();
        return view('admin.dashboard', compact('pending','validated','rejected','settings'));
    }

    public function toggleAutoPublish(Request $request)
    {
        $s = SiteSetting::current();
        $s->update(['auto_publish' => !$s->auto_publish]);
        return back()->with('success','Paramètre mis à jour.');
    }

    public function updateIntro(Request $request)
    {
        $data = $request->validate(['intro_html' => 'required|string']);
        SiteSetting::current()->update(['intro_html'=>$data['intro_html']]);
        return back()->with('success','Contenu d\'intro mis à jour.');
    }

    public function approve(Article $article)
    {
        $article->update(['status'=>'validated','rejection_reason'=>null,'published_at'=>now(),'is_published'=>true]);

        // Notifier l\'auteur (mail+db+broadcast)
        $article->user->notify(new ArticleStatusNotification($article, 'validé'));
        Mail::to($article->user->email)
            ->send(new ArticleStatusNotification($article, 'approuvé'));

        // Notifier les abonnés de l\'auteur (article validé uniquement)
        foreach ($article->user->followers as $follower) {
            $follower->notify(new ArticleApprovedNotification($article));
        }

        return back()->with('success','Article validé.');
    }

    public function reject(Request $request, Article $article)
    {
        $data = $request->validate(['reason' => 'required|string|min:3']);
        $article->update(['status'=>'rejected','rejection_reason'=>$data['reason'],'is_published'=>false]);

        // Notifier l\'auteur avec motif
        $article->user->notify(new ArticleStatusNotification($article, 'rejeté', $data['reason']));
        Mail::to($article->user->email)
            ->send(new ArticleStatusNotification($article, 'rejeté', $request->reason));
        
        return back()->with('success','Article rejeté.');
    }
}


