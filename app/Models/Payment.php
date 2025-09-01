<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $casts = [
        'meta' => 'array', // Pour stocker les données JSON (ex: article_id)
    ];

    protected $fillable = [
        'user_id',
        'transaction_id',
        'reference',     // Référence unique du paiement
        'amount',
        'status',        // success, failed, cancelled, pending
        'purpose',       // boost_article ou abonnement
        'meta',          // informations supplémentaires (JSON)
    ];

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
