<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model{
    protected $table = 'plan';
    protected $fillable = [
        'name','duration_days','price',
        'payment_provider','payment_link','is_active'
    ];

    protected $casts = [
        'is_active'=>'boolean',
    ];

    public function subscriptions(){
        return $this->hasMany(Subscription::class);
    }

    public function scopeActive($q){ return $q->where('is_active', true); }
}
