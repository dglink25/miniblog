<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model {
    protected $table = 'subscription';
    protected $fillable = [
        'user_id','plan_id','starts_at','ends_at','status',
        'payment_ref','paid_amount','verification_code'
    ];
    protected $casts = ['starts_at'=>'datetime','ends_at'=>'datetime'];

    public function plan(){ return $this->belongsTo(Plan::class); }
    public function user(){ return $this->belongsTo(User::class); }

    public function isActive(): bool {
        return $this->status === 'active' && now()->between($this->starts_at, $this->ends_at);
    }
}
