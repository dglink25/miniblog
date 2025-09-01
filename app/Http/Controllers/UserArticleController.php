<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserArticleController extends Controller{
    public function index(User $user){
        $articles = $user->articles()->latest()->paginate(10); // pagination

        return view('users.articles', compact('user', 'articles'));
    }
}
