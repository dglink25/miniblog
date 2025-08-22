<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function __construct() { $this->middleware('auth'); }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'stars' => 'required|integer|min:1|max:5',
        ]);

        Rating::updateOrCreate(
            ['user_id' => auth()->id()],
            ['stars' => $data['stars']]
        );

        return back()->with('success', 'Merci pour votre note !');
    }
}