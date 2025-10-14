<?php

// app/Http/Controllers/SitemapController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Carbon\Carbon;

class SitemapController extends Controller
{
    public function index()
    {
        $articles = Article::latest()->get();

        return response()->view('sitemap', [
            'articles' => $articles,
        ])->header('Content-Type', 'text/xml');
    }
}
