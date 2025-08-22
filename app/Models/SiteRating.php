<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteRating extends Model{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stars',
    ];

    // Un rating appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
