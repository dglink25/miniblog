<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class SuggestionController extends Controller
{

    public function create(){ return view('suggestions/create'); }
    public function store(Request $r){
        $data = $r->validate([
            'subject'=>'nullable|string|max:255',
            'message'=>'required|string|min:10',
        ]);
        $data['user_id'] = auth()->id();
        Suggestion::create($data);
        return redirect()->route('articles.index')->with('success','Suggestion envoyée. Merci !');
    }

}
