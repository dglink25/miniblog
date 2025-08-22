<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IntroPage{
    public function handle($request, Closure $next){
        if (!$request->cookies->has('seen_intro')) {
            return redirect()->route('intro.show');
        }
        return $next($request);
    }

}
