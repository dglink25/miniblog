<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // commenter = connecté obligatoire
    }

    
    public function store(Request $request, Article $article)
    {
        $request->validate([
            'body' => 'required|min:10',
        ]);

        $article->comments()->create([
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        return back()->with('success', 'Commentaire publié !');
    }

}
