<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    use Queueable, SerializesModels;

    public function __construct(public Subscription $subscription) {}

    public function build() {
        return $this->subject('Votre code d’activation d’abonnement')
            ->markdown('emails.subscription_code', [
                'sub'=>$this->subscription
            ]);
    }
    
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Subscription Code Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.subscription_code',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
    
}
