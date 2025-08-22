<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class SuggestionController extends Controller
{
    public function __construct() { $this->middleware('auth'); }

    public function create()
    {
        return view('suggestions.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'message' => 'required|string|min:10',
        ]);

        Suggestion::create([
            'user_id' => auth()->id(),
            'message' => $data['message'],
        ]);

        return redirect()->route('suggestions.create')
            ->with('success', 'Suggestion envoyée. Merci !');
    }
}
