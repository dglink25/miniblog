<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // commenter = connecté obligatoire
    }

    public function store(StoreCommentRequest $request, Article $article): RedirectResponse
    {
        Comment::create([
            'article_id' => $article->id,
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        return back()->with('success', 'Commentaire ajouté !');
    }
}
