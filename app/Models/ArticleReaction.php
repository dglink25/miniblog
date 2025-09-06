<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleReaction extends Model{
    protected $fillable = ['article_id','user_id','type'];

    public function article() {
        return $this->belongsTo(Article::class);
    }
}

