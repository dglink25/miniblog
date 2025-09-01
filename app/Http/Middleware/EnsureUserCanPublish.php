namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserCanPublish
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->canPublish()) {
            return redirect()->route('subscriptions.plans')
                ->with('error','Votre abonnement est inactif ou expiré. Veuillez vous abonner.');
        }
        return $next($request);
    }
}
