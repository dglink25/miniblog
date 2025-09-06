<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\SiteSetting;
use Purifier;
use App\Models\Announcement;


class ArticleController extends Controller{
    public function __construct(){
        // Accès public pour index/show ; reste protégé
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Request $request){
        $q = $request->string('q')->toString();

        $articles = Article::with('user')
            ->when($q, fn($query) => $query->where(function($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('content', 'like', "%{$q}%");
            }))
            ->where('status', 'validated')
            ->orderByDesc('pinned')      // 🔹 les articles épinglés en premier
            ->latest('published_at')     // puis par date de publication
            ->paginate(5)
            ->withQueryString();

        $annonces = Announcement::published()
            ->when(auth()->check(), function($q) {
                $q->whereNotIn('id', function($sub){
                    $sub->select('announcement_id')
                        ->from('announcement_user_dismissals')
                        ->where('user_id', auth()->id());
                });
            })
            ->orderByDesc('is_pinned') // épinglés en tête
            ->orderByDesc('published_at')
            ->take(3) // limite si tu veux
            ->get();

        return view('articles.index', compact('articles','q','annonces'));
    }

    public function create()
    {
        return view('articles.create');
    }

    public function store(StoreArticleRequest $request): RedirectResponse{
        
        if (! auth()->user()->hasActiveTrial() && ! auth()->user()->has_subscription) {
            return redirect()->route('subscriptions.plans')
                ->with('error', 'Votre essai gratuit est terminé. Souscrivez un abonnement pour continuer à publier.');
        }


        $validated = $request->validated();

        $path = $request->file('image')
            ? $request->file('image')->store('articles', 'public')
            : null;

        $data['content'] = \Purifier::clean($request->input('content'));
        $settings = \App\Models\SiteSetting::current();
        $auto = (bool) $settings->auto_publish;

        $article = Article::create([
            'user_id'       => auth()->id(),
            'title'         => $validated['title'],
            'content'       => $data['content'],
            'image_path'    => $path,
            'status'        => $auto ? 'validated' : 'pending',
            'published_at'  => $auto ? now() : null,
            'is_published'  => $auto, // bool direct
        ]);

        // Médias multiples
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('articles', 'public');

                $mime = $file->getMimeType();
                $type = str_starts_with($mime, 'image') ? 'image'
                        : (str_starts_with($mime, 'video') ? 'video' : 'file');

                $article->media()->create([
                    'file_path' => $path,
                    'mime_type' => $mime,
                    'type' => $type,
                ]);
            }
        }

        return redirect()->route('articles.mine')
            ->with('success', 'Article enregistré, en attente de validation par administrateur!');
    }


    public function show(Article $article){
        if (!$article->is_published) {
            if (!auth()->check() || (auth()->id() !== $article->user_id && !auth()->user()->is_admin)) {
                abort(404);
            }
        }
        $article->load(['user','comments.user']);
        return view('articles.show', compact('article'));
    }

    public function edit(Article $article){
        $this->authorize('update', $article);
        return view('articles.edit', compact('article'));
    }

    public function update(UpdateArticleRequest $request, Article $article): RedirectResponse{
        $this->authorize('update', $article);

        $data = $request->only('title','content');

        if ($request->hasFile('image')) {
            // Supprime l'ancienne image si présente
            if ($article->image_path) {
                Storage::disk('public')->delete($article->image_path);
            }
            $data['image_path'] = $request->file('image')->store('articles', 'public');
        }

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('articles/media', 'public');
                $mime = $file->getClientMimeType();
                $type = str_contains($mime, 'image') ? 'image' : (str_contains($mime, 'video') ? 'video' : 'file');
                $article->media()->create([
                    'file_path' => $path,
                    'mime_type' => $mime,
                    'type' => $type,
                ]);
            }
        }

        $article->update($data);

        return redirect()->route('articles.show', $article)
            ->with('success', "L'article a été mis à jour.");
    }

    public function destroy(Article $article): RedirectResponse
    {
        $this->authorize('delete', $article);


        foreach ($article->media as $m) {
            Storage::disk('public')->delete($m->file_path);
        }

        if ($article->image_path) {
            Storage::disk('public')->delete($article->image_path);
        }

        $article->delete();

        return redirect()->route('articles.index')
            ->with('success', "L'article a été supprimé.");
    }

    public function mine(){
        $userId = auth()->id();
        $pending = \App\Models\Article::with('user','media')->where('user_id',$userId)->pending()->latest()->get();
        $validated = \App\Models\Article::with('user','media')->where('user_id',$userId)->published()->latest('published_at')->get();
        $rejected = \App\Models\Article::with('user','media')->where('user_id',$userId)->rejected()->latest()->get();
        return view('articles.mine', compact('pending','validated','rejected'));
    }

    // app/Http/Controllers/ArticleController.php
    public function byUser(\App\Models\User $user){
        $articles = $user->articles()
            ->with(['media','user'])
            ->where('is_published', true)
            ->latest('published_at')
            ->paginate(9);
        return view('users.articles', compact('user','articles'));
    }
    
    public function react(Request $request, Article $article){
        $request->validate(['type' => 'required|in:like,dislike']);

        $article->reactions()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['type' => $request->type]
        );

        return back()->with('success', "Réaction enregistrée !");
    }




}
