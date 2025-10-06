<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteRating;

class SiteRatingController extends Controller{
    public function store(Request $request)
    {
        $request->validate([
            'stars' => 'required|integer|min:1|max:5',
        ]);

        SiteRating::create([
            'user_id' => auth()->id(),
            'stars'   => $request->stars,
        ]);

        return back()->with('success', 'Merci pour votre note !');
    }
    
}
