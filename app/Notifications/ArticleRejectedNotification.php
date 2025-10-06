<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ArticleRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(public $article, public $reason) {}
    public function via($notifiable){ return ['mail','database']; }
    public function toMail($notifiable){
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Votre article a été rejeté ❌')
            ->greeting('Bonjour '.$notifiable->name)
            ->line("L’article « {$this->article->title} » a été rejeté.")
            ->line("Motif : {$this->reason}");
    }
    public function toDatabase($notifiable){
        return ['article_id'=>$this->article->id,'title'=>$this->article->title,'type'=>'rejected','reason'=>$this->reason];
    }


}
