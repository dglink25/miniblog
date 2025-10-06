<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiteSetting extends Controller
{
    protected $fillable = ['auto_publish'];

    public static function current()
    {
        return self::first(); // ⚡ doit renvoyer un enregistrement
    }
}
