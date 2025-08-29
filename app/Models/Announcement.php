<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    protected $fillable = [
        'title','content_html','media_type','media_url',
        'is_published','is_pinned','published_at','created_by'
    ];
    protected $casts = [
        'is_published'=>'bool','is_pinned'=>'bool','published_at'=>'datetime'
    ];

    public function author(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($q) {
        return $q->where('is_published', true);
    }
}