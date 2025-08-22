<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TinyMCEController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $path = $request->file('file')->store('public/uploads');
        $url = Storage::url($path);

        return response()->json(['location' => $url]);
    }
}

