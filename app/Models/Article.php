<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id','title','slug','content','image_path','status','rejection_reason','published_at'
    ];

    public function ratings() { return $this->hasMany(\App\Models\ArticleRating::class); }


    // Scopes utiles
    public function scopePublished($q) { return $q->where('status','validated'); }
    public function scopePending($q) { return $q->where('status','pending'); }
    public function scopeRejected($q) { return $q->where('status','rejected'); }


    public function averageStars(): float { return (float) ($this->ratings()->avg('stars') ?? 0); }

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

    //public function scopePublished($q) { return $q->where('is_published', true); }

    public function getStatusLabelAttribute(): string{
        return $this->is_published ? 'Publié' : 'En attente';
    }


}
