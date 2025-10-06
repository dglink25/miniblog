<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Article;

class FavoriteController extends Controller{
    public function toggle(Article $article){
        $user = auth()->user();

        if ($user->favorites()->where('article_id', $article->id)->exists()) {
            $user->favorites()->detach($article->id);
            return back()->with('success', 'Article retiré des favoris.');
        }

        $user->favorites()->attach($article->id);
        return back()->with('success', 'Article ajouté aux favoris.');
    }

    public function index(){
        $articles = auth()->user()
            ->favorites()
            ->with(['user','media']) // si tu as une relation media
            ->latest()
            ->paginate(9);

        return view('favorites.index', compact('articles'));
    }
    

}

