<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Support\Carbon;

class Subscription extends Model{
    protected $table = 'subscription';
    protected $fillable = [
        'user_id','plan_id',
        'status','source',
        'verification_code',
        'paid_amount','paid_at','payment_link','metadata',
        'starts_at','ends_at',
    ];

    protected $casts = [
        'metadata'=>AsArrayObject::class,
        'starts_at'=>'datetime',
        'ends_at'=>'datetime',
        'paid_at'=>'datetime',
    ];

    public function user(){ return $this->belongsTo(User::class); }
    public function plan(){ return $this->belongsTo(Plan::class); }

    public function scopeRecent($q){ return $q->orderByDesc('id'); }
    public function scopeFilter($q, array $f){
        if (!empty($f['status'])) $q->where('status',$f['status']);
        if (!empty($f['source'])) $q->where('source',$f['source']);
        if (!empty($f['plan_id'])) $q->where('plan_id',$f['plan_id']);
        if (!empty($f['from'])) $q->whereDate('created_at','>=',$f['from']);
        if (!empty($f['to']))   $q->whereDate('created_at','<=',$f['to']);
        if (!empty($f['q']))    $q->whereHas('user', fn($u)=>$u->where('email','like','%'.$f['q'].'%')->orWhere('name','like','%'.$f['q'].'%'));
        return $q;
    }

    public function getIsActiveAttribute(): bool {
        return $this->status === 'active' && $this->ends_at && $this->ends_at->isFuture();
    }

    public function activateNow(): void {
        $days = $this->plan?->duration_days ?? 0;
        $now = now();
        $this->update([
            'status'=>'active',
            'starts_at'=>$now,
            'ends_at'=>$now->copy()->addDays($days),
        ]);
    }
}

