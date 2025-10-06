<?php  

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequirePublishAccess
{
    public function handle(Request $request, Closure $next) {
        if (!auth()->user()->canPublish()) {
            return redirect()->route('subscriptions.plans')
                ->with('error','Votre essai a expiré. Abonnez-vous pour publier.');
        }
        return $next($request);
    }
}
