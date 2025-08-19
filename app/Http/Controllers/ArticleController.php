<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function __construct()
    {
        // Accès public pour index/show ; reste protégé
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $articles = Article::with('user')
            ->when($q, function ($query) use ($q) {
                $query->where(function($sub) use ($q) {
                    $sub->where('title', 'like', "%{$q}%")
                        ->orWhere('content', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('articles.index', compact('articles','q'));
    }

    public function create()
    {
        return view('articles.create');
    }

    public function store(StoreArticleRequest $request): RedirectResponse
    {
        $path = $request->file('image')->store('articles', 'public');

        $article = Article::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
            'image_path' => $path,
        ]);

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

        return redirect()->route('articles.show', $article)
            ->with('success', 'Article publié avec succès.');
    }

    public function show(Article $article)
    {
        $article->load(['user', 'comments.user']);
        return view('articles.show', compact('article'));
    }

    public function edit(Article $article)
    {
        $this->authorize('update', $article);
        return view('articles.edit', compact('article'));
    }

    public function update(UpdateArticleRequest $request, Article $article): RedirectResponse
    {
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
}
