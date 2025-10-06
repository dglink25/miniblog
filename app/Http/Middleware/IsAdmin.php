<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifie si l'utilisateur est connecté et admin
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request);
        }

        // Sinon redirection (par ex. vers accueil)
        return redirect()->route('home')->with('error', 'Accès interdit');
    }
}
