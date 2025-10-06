<?php
namespace App\Notifications;
use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ArticleApprovedNotification extends Notification
{
    use Queueable;
    public function __construct(public Article $article){}
    public function via($notifiable){ return ['mail','database','broadcast']; }
    public function toMail($notifiable){
        return (new MailMessage)
            ->subject('Nouvel article validé')
            ->greeting('Bonjour '.$notifiable->name)
            ->line("{$this->article->user->name} vient de publier : '{$this->article->title}'.")
            ->action('Lire', route('articles.show',$this->article));
    }
    public function toArray($notifiable){
        return ['article_id'=>$this->article->id,'title'=>$this->article->title,'author'=>$this->article->user->name];
    }
    public function toBroadcast($notifiable){ return new BroadcastMessage($this->toArray($notifiable)); }


    public function toDatabase($notifiable){
        return ['article_id'=>$this->article->id,'title'=>$this->article->title,'type'=>'approved'];
    }

}