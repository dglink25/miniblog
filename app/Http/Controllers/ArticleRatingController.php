<?php
namespace App\Http\Controllers;
use App\Models\Article;
use App\Models\ArticleRating;
use Illuminate\Http\Request;

class ArticleRatingController extends Controller
{
    public function __construct(){ $this->middleware('auth'); }

    public function store(Request $request, Article $article)
    {
        $data = $request->validate(['stars' => 'required|integer|min:1|max:5']);
        $exists = ArticleRating::where('user_id', auth()->id())->where('article_id', $article->id)->exists();
        if ($exists) return back()->with('error','Vous avez déjà noté cet article.');

        ArticleRating::create([
            'user_id' => auth()->id(),
            'article_id' => $article->id,
            'stars' => $data['stars'],
        ]);
        return back()->with('success','Merci pour votre note !');
    }
}