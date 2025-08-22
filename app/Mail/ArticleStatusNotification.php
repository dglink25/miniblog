<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Article;

class ArticleStatusNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $article;
    public $status;
    public $reason;

    public function __construct(Article $article, string $status, ?string $reason = null)
    {
        $this->article = $article;
        $this->status = $status; // "approuvé" ou "rejeté"
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject("Statut de votre article : {$this->status}")
                    ->view('emails.article_status');
    }
}