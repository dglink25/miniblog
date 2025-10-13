<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller{
    public function __construct()
    {
        $this->middleware('auth'); // commenter = connecté obligatoire
    }

    
    public function store(Request $request, Article $article)
    {
        $request->validate([
            'body' => 'required|min:1',
        ]);

        $article->comments()->create([
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        return back()->with('success', 'Commentaire publié !');
    }
    public function reply(Request $request, Comment $comment)
    {
        $request->validate([
            'body' => 'required|string|max:100000',
        ]);

        $reply = new Comment();
        $reply->body = $request->body;
        $reply->user_id = auth()->id();
        $reply->article_id = $comment->article_id;
        $reply->parent_id = $comment->id;
        $reply->save();

        return back()->with('success', 'Réponse ajoutée.');
    }
    public function react(Request $request, Comment $comment){
        $request->validate(['type' => 'required|string']);

        $reaction = $comment->reactions()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['type' => $request->type]
        );

        return back();
    }

    public function edit(Comment $comment){
        if (!$comment->canEditOrDelete()) {
            return back()->withErrors("Vous ne pouvez plus modifier ce commentaire.");
        }
        return view('comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment){
        if (!$comment->canEditOrDelete()) {
            return back()->withErrors('Vous ne pouvez plus modifier ce commentaire (délai dépassé).');
        }

        $request->validate([
            'body' => 'required|string|max:1000000',
        ]);

        $comment->update(['body' => $request->body]);

        return back()->with('success', 'Commentaire modifié avec succès.');
    }

    public function destroy(Comment $comment){
        if (!$comment->canEditOrDelete()) {
            return back()->withErrors('Vous ne pouvez plus supprimer ce commentaire (délai dépassé).');
        }

        $comment->delete();

        return back()->with('success', 'Commentaire supprimé.');
    }




}
