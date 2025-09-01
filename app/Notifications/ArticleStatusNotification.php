<?php
namespace App\Notifications;
use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ArticleStatusNotification extends Notification
{
    use Queueable;
    public function __construct(public Article $article, public string $status, public ?string $reason=null){}
    public function via($notifiable){ return ['mail','database']; }
    public function toMail($notifiable){
        return (new MailMessage)
            ->subject('Statut de votre article')
            ->greeting('Bonjour '.$notifiable->name)
            ->line("Votre article '{$this->article->title}' a été {$this->status}.")
            ->when($this->reason, fn($m)=>$m->line('Motif : '.$this->reason))
            ->action('Voir l\'article', route('articles.show',$this->article));
    }
    public function toArray($notifiable){
        return [
            'article_id'=>$this->article->id,
            'title'=>$this->article->title,
            'status'=>$this->status,
            'reason'=>$this->reason,
        ];
    }
    public function toBroadcast($notifiable){ return new BroadcastMessage($this->toArray($notifiable)); }
}