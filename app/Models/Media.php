<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = ['article_id','file_path','mime_type','type'];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function isImage()
    {
        return str_contains($this->mime_type, 'image');
    }

    public function isVideo()
    {
        return str_contains($this->mime_type, 'video');
    }
    public function isAudio(): bool{
        return str_starts_with($this->mime_type, 'audio/');
    }
}
