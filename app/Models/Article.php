<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'slug', 'content', 'image_path',
    ];

    // Relations
    public function user() { return $this->belongsTo(User::class); }
    public function comments() { return $this->hasMany(Comment::class)->latest(); }

    // Utiliser le slug dans les URLs
    public function getRouteKeyName(): string { return 'slug'; }

    protected static function booted()
    {
        static::creating(function (Article $article) {
            $article->slug = static::uniqueSlug($article->title);
        });
        static::updating(function (Article $article) {
            if ($article->isDirty('title')) {
                $article->slug = static::uniqueSlug($article->title, $article->id);
            }
        });
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 2;
        while (static::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$i++;
        }
        return $slug;
    }
    public function media()
    {
        return $this->hasMany(Media::class);
    }

}
