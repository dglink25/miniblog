<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['article_id', 'user_id', 'body'];

    public function article() { return $this->belongsTo(Article::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function replies(){
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function parent(){
        return $this->belongsTo(Comment::class, 'parent_id');
    }
    public function reactions(){
        return $this->hasMany(CommentReaction::class);
    }

    public function canEditOrDelete(): bool{
        return $this->user_id === auth()->id() 
            && $this->created_at->gt(now()->subMinutes(15));
    }



}
