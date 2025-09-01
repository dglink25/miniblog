<?php 

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionCodeMail;

class Subscription_Controller extends Controller{
    public function plans(){
        $active = auth()->check() ? auth()->user()->activeSubscription() : null;
        $plans = Plan::active()->orderBy('price')->get();
        return view('subscriptions.plans', compact('plans','active'));
    }

    public function checkout(Request $r, Plan $plan){
        $this->authorize('purchase', $plan);

        if (!$plan->is_active || $plan->payment_provider !== 'kia' || empty($plan->payment_link)) {
            return back()->withErrors('Plan non disponible au paiement.');
        }

        $articleId = $r->input('article_id', null); // facultatif

        $sub = Subscription::create([
            'user_id' => auth()->id(),
            'plan_id' => $plan->id,
            'status' => 'pending',
            'source' => 'kia',
            'metadata' => [
                'ua' => $r->userAgent(),
                'ip' => $r->ip(),
                'article_id' => $articleId,
            ],
        ]);

        $successUrl = $articleId 
            ? route('boost.success', ['article' => $articleId], true) // URL absolue
            : route('subscriptions.plans', [], true);                 // URL absolue

        $cancelUrl = route('payments.history', [], true);            // URL absolue


        
        $query = http_build_query([
            'reference' => $sub->id,
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
        ]);

        return redirect()->away($plan->payment_link.'?'.$query);
    }




    public function verifyForm()
    {
        $lastPending = auth()->user()->subscriptions()->where('status','pending')->latest()->first();
        return view('subscriptions.verify', compact('lastPending'));
    }

    public function verifyCode(Request $r){
        $r->validate(['code'=>'required|string']);
        $sub = auth()->user()->subscriptions()->where('status','pending')->latest()->first();
        if (!$sub) return back()->withErrors('Aucune souscription en attente.');

        if (trim($r->code) !== $sub->verification_code) {
            return back()->withErrors('Code invalide.');
        }

        $sub->activateNow();
        return redirect()->route('articles.index')->with('success','Abonnement activé!');
    }
}
