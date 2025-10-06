<?php
namespace App\Http\Middleware;
use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class ShowIntro
{
    public function handle(Request $request, Closure $next): Response
    {
        $settings = SiteSetting::current();
        if (!$settings->intro_enabled) return $next($request);


        // Ne pas intercepter l’intro elle‑même, l’admin, l’auth, api, etc.
        if ($request->is('intro*') || $request->is('admin*') || $request->is('login') || $request->is('register') || $request->is('password/*')) {
            return $next($request);
        }


        $seen = session('seen_intro');
        $user = $request->user();
        $needsIntro = false;


        if (!$seen) { $needsIntro = true; }
        if ($user && $user->last_seen_at && now()->diffInDays($user->last_seen_at) >= 2) {
            $needsIntro = true;
        }


        if ($needsIntro) {
            return redirect()->route('intro');
        }


        return $next($request);
    }
}