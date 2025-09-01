<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionCodeMail extends Mailable{
    use Queueable, SerializesModels;

    public function __construct(public Subscription $subscription){}

    public function build()
    {
        return $this->subject('Code d’activation abonnement')
            ->markdown('emails.subscription.code', [
                'sub'=>$this->subscription
            ]);
    }
}
