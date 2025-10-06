<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionCodeMail;

class SubscriptionAdminController extends Controller
{
    public function __construct(){ $this->middleware(['auth','can:admin']); }

    public function index(Request $r){
        $filters = $r->only('status','source','plan_id','from','to','q');
        $subs = Subscription::with(['user','plan'])
            ->filter($filters)->recent()->paginate(20);
        $plans = Plan::orderBy('price')->get();
        return view('admin.subscriptions.index', compact('subs','plans','filters'));
    }

    public function markPaidAndSendCode(Subscription $subscription){
        if ($subscription->status !== 'pending') {
            return back()->withErrors('Abonnement non en attente.');
        }
        $subscription->update([
            'paid_at'=>now(),
            'paid_amount'=>$subscription->plan?->price,
        ]);
        Mail::to($subscription->user->email)->send(new SubscriptionCodeMail($subscription));
        return back()->with('success','Marqué payé et code envoyé.');
    }

    public function activate(Subscription $subscription){
        if ($subscription->status === 'active' && $subscription->ends_at?->isFuture()) {
            return back()->withErrors('Déjà actif.');
        }
        $subscription->activateNow();
        return back()->with('success','Abonnement activé.');
    }

    // Accorder un accès manuel (source=admin)
    public function grantForm(){
        return view('admin.subscriptions.grant', [
            'users'=> User::orderBy('name')->limit(100)->get(),
            'plans'=> Plan::active()->orderBy('price')->get(),
        ]);
    }

    public function grantStore(Request $r){
        $data = $r->validate([
            'user_id'=>'required|exists:users,id',
            'plan_id'=>'required|exists:plans,id',
            'days'=>'nullable|integer|min:1', // si vide -> durée du plan
            'send_code'=>'sometimes|boolean',
        ]);

        $plan = Plan::findOrFail($data['plan_id']);
        $code = strtoupper(Str::random(8));
        $sub = Subscription::create([
            'user_id'=>$data['user_id'],
            'plan_id'=>$plan->id,
            'status'=>'active',
            'source'=>'admin',
            'verification_code'=>$code,
        ]);
        // activer immédiatement avec durée
        $now = now();
        $days = $data['days'] ?? $plan->duration_days;
        $sub->update([
            'starts_at'=>$now,
            'ends_at'=>$now->copy()->addDays($days),
        ]);

        if ($r->boolean('send_code')) {
            Mail::to($sub->user->email)->send(new \App\Mail\SubscriptionCodeMail($sub));
        }

        return redirect()->route('admin.subscriptions.index')->with('success','Accès accordé.');
    }
}
