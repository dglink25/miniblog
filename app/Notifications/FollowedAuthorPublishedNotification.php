<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FollowedAuthorPublishedNotification extends Notification
{
    use Queueable;

    public function __construct(public $article) {}
    public function via($notifiable){ return ['mail','database']; }
    public function toMail($notifiable){
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject("Nouvel article de {$this->article->user->name}")
            ->line("{$this->article->user->name} a publié : {$this->article->title}")
            ->action('Lire', route('articles.show', $this->article));
    }
    public function toDatabase($notifiable){
        return [
            'article_id'=>$this->article->id,
            'author'=>$this->article->user->name,
            'title'=>$this->article->title,
            'type'=>'followed_published'
        ];
    }

}
