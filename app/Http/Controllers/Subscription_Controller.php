<?php 

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class Subscription_Controller extends Controller
{
    public function plans() {
        $plans = Plan::where('is_active', true)->orderBy('price')->get();
        $user = auth()->user();
        $active = $user?->activeSubscription();
        $settings = SiteSetting::current();
        $trialEndsAt = $user ? $user->created_at->copy()->addDays($settings->trial_days ?? 50) : null;

        return view('subscriptions/plans', compact('plans','active','trialEndsAt','settings'));
    }

    public function checkout(Request $request, Plan $plan){
        // 1️⃣ Vérification plan
        if (!$plan) {
            return back()->withErrors('Plan invalide.');
        }

        // 2️⃣ Création subscription "pending"
        $code = strtoupper(Str::random(8));
        $sub = Subscription::create([
            'user_id' => auth()->id(),
            'plan_id' => $plan->id,
            'starts_at' => now(),
            'ends_at' => now()->addDays($plan->duration_days),
            'status' => 'pending',
            'verification_code' => $code,
        ]);

        // 3️⃣ Callback sécurisé (ngrok pour dev / HTTPS prod)
        $callback = config('app.env') === 'production'
            ? route('fedapay.callback')
            : 'https://93d4b6a8a2c4.ngrok-free.app/fedapay/callback';

        $desc = 'Abonnement ' . $plan->name . ' (#' . $sub->id . ')';

        try {
            // 4️⃣ Appel API FedaPay
            $res = Http::withToken(config('services.fedapay.secret'))
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post(config('services.fedapay.base') . '/transactions', [
                    'amount' => $plan->price * 100, // en centimes
                    'currency' => config('services.fedapay.currency', 'XOF'),
                    'description' => $desc,
                    'callback_url' => $callback,
                    'metadata' => [
                        'subscription_id' => $sub->id,
                        'user_id' => auth()->id(),
                    ],
                ]);

            // 5️⃣ Logging pour debug
            \Log::debug('FedaPay request', [
                'url' => config('services.fedapay.base') . '/transactions',
                'payload' => [
                    'amount' => $plan->price * 100,
                    'currency' => config('services.fedapay.currency', 'XOF'),
                    'description' => $desc,
                    'callback_url' => $callback,
                    'metadata' => [
                        'subscription_id' => $sub->id,
                        'user_id' => auth()->id(),
                    ],
                ],
            ]);
            \Log::debug('FedaPay response', [
                'status' => $res->status(),
                'body' => $res->body(),
                'headers' => $res->headers(),
            ]);

            // 6️⃣ Vérification réponse
            if ($res->failed()) {
                $sub->delete();
                $msg = $res->body() ?: 'Erreur paiement. Réessaie plus tard.';
                return back()->withErrors($msg);
            }

            $data = $res->json();

            // 7️⃣ Récupération URL paiement
            $checkoutUrl = $data['checkout_url'] ?? ($data['data']['url'] ?? null);
            if (!$checkoutUrl) {
                $sub->delete();
                return back()->withErrors('Paiement indisponible. Essaie plus tard.');
            }

            // 8️⃣ Redirection sécurisée vers FedaPay
            return redirect()->away($checkoutUrl);

        } catch (\Exception $e) {
            // 9️⃣ Gestion exception réseau / API
            $sub->delete();
            \Log::error('FedaPay exception', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors('Erreur serveur paiement. Réessaie plus tard.');
        }
    }



    // Webhook/callback FedaPay
    public function fedapayCallback(Request $r) {
        $payload = $r->all();

        $status = $payload['status'] ?? $payload['data']['status'] ?? null;
        $subscriptionId = $payload['metadata']['subscription_id'] ?? $payload['data']['metadata']['subscription_id'] ?? null;
        $transactionRef = $payload['reference'] ?? $payload['data']['reference'] ?? null;
        $amount = $payload['amount'] ?? $payload['data']['amount'] ?? null;

        if (!$subscriptionId) return response()->json(['ok'=>true]);

        $sub = Subscription::find($subscriptionId);
        if (!$sub) return response()->json(['ok'=>true]);

        // Paiement réussi
        if ($status === 'paid' || $status === 'approved' || $status === 'succeeded') {
            $sub->update([
                'payment_ref'=>$transactionRef,
                'paid_amount'=>$amount,
                'status'=>'pending', // reste pending jusqu'à code
            ]);

            // Envoi du code par e-mail
            Mail::to($sub->user->email)->send(new \App\Mail\SubscriptionCodeMail($sub));
        }

        return response()->json(['ok'=>true]);
    }


    public function verifyForm() {
        $lastPending = auth()->user()->subscription()->where('status','pending')->latest()->first();
        return view('subscription/verify', compact('lastPending'));
    }

    public function verifyCode(Request $r) {
        $r->validate(['code'=>'required|string']);
        $sub = auth()->user()->subscription()->where('status','pending')->latest()->first();
        if (!$sub) return back()->withErrors('Aucune souscription en attente.');

        if (trim($r->code) === $sub->verification_code) {
            $sub->update(['status'=>'active']);
            return redirect()->route('articles.index')->with('success','Abonnement activé 👍');
        }
        return back()->withErrors('Code invalide.');
    }
}
