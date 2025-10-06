<?php

namespace App\Http\Controllers;

use App\Models\Payment;   
use App\Models\Article;   
use Illuminate\Http\Request;

class PaymentController extends Controller{
    // Callback serveur KiaPay (notification)
    public function kiapayCallback(Request $request){
        $signature = $request->header('X-Signature'); 
        $payload = $request->getContent();

        if (! hash_equals(hash_hmac('sha256', $payload, env('KIAPAY_SECRET')), $signature)) {
            abort(403, 'Signature invalide');
        }

        $status = $request->input('status'); 
        $reference = $request->input('reference'); 

        $payment = Payment::where('reference', $reference)->first();
        if (! $payment) {
            return response()->json(['error' => 'Paiement introuvable'], 404);
        }

        // Mise à jour du statut
        $payment->status = $status;
        $payment->save();

        if ($status === 'success') {
            if ($payment->purpose === 'boost_article') {
                $articleId = $payment->meta['article_id'] ?? null;
                if ($articleId) {
                    Article::where('id', $articleId)->update(['is_boosted' => true]);
                }
            }

            if ($payment->purpose === 'abonnement') {
                $payment->user->update(['has_subscription' => true]);
            }
        }

        return response()->json(['message' => 'Callback traité avec succès']);
    }
    public function boostSuccess(Article $article){
        return redirect()->route('articles.mine')
            ->with('success', "Votre article '{$article->title}' est maintenant boosté 🚀");
    }


    // Retour utilisateur après paiement
    public function kiapayReturn(Request $request){
        $reference = $request->input('reference');
        $payment = Payment::where('reference', $reference)->first();

        if (! $payment) {
            return redirect()->route('payments.history')->with('error', 'Paiement introuvable.');
        }

        if ($payment->status === 'success') {
            if ($payment->purpose === 'boost_article') {
                return redirect()->route('boost.success', ['article' => $payment->meta['article_id'] ?? null])
                    ->with('success', 'Paiement réussi, article boosté 🚀');
            }

            if ($payment->purpose === 'abonnement') {
                return redirect()->route('subscriptions.index')->with('success', 'Votre abonnement est activé ✅');
            }
        }

        if ($payment->status === 'cancelled') {
            return redirect()->route('payments.history')->with('error', 'Paiement annulé.');
        }

        return redirect()->route('payments.history')->with('error', 'Échec du paiement.');
    }

    public function history()
    {
        $payments = Payment::where('user_id', auth()->id())->latest()->get();
        return view('payments.history', compact('payments'));
    }

    public function boost(Request $request, Article $article)
    {
        if (! auth()->user()->has_subscription) {
            return redirect()->route('subscriptions.plans')
                ->with('error', 'Vous devez souscrire un abonnement pour booster.');
        }

        $article->update(['is_boosted' => true]);

        return back()->with('success', 'Votre article est boosté avec succès 🚀');
    }
    // Dans Subscription_Controller ou PaymentController
    public function checkout(Request $r, Plan $plan, $articleId = null){
        $plan->payment_link = 'https://pay.kiapay.me/...';
        $plan->success_url = route('boost.success', ['article' => $articleId]);  // après paiement réussi
        $plan->cancel_url  = route('payments.history');                           // après annulation

        $payment = Payment::create([
            'user_id' => auth()->id(),
            'plan_id' => $plan->id,
            'status' => 'pending',
            'purpose' => $articleId ? 'boost_article' : 'abonnement',
            'meta' => ['article_id' => $articleId],
        ]);

        $query = http_build_query([
            'reference' => $payment->reference,
            'success_url' => $articleId 
                ? route('boost.success', ['article' => $articleId]) 
                : route('subscriptions.index'),
            'cancel_url' => route('payments.history'),
        ]);

        return redirect()->away($plan->payment_link.'?'.$query);
    }

}
